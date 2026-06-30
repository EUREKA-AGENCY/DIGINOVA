<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import { Plus, Trash2, ChevronDown } from 'lucide-vue-next';

interface InvoiceItem {
    id: number;
    invoice_number: string;
    client_name: string;
    client_email: string;
    item_name: string;
    years: number;
    total_amount: number;
    status: 'pending' | 'paid' | 'cancelled';
    created_at: string;
}

interface Company {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    bp: string | null;
    siege_social: string | null;
    address: string | null;
}

interface Props {
    invoices: InvoiceItem[];
    status?: string | null;
}

defineProps<Props>();

function fmt(n: number) {
    return new Intl.NumberFormat('fr-FR').format(n);
}

function markPaid(invoice: InvoiceItem) {
    if (!confirm(`Marquer la facture ${invoice.invoice_number} comme payée ?`)) return;
    useForm({}).post(route('admin.invoices.mark-paid', invoice.id), { preserveScroll: true });
}

function markCancelled(invoice: InvoiceItem) {
    if (!confirm(`Annuler la facture ${invoice.invoice_number} ?`)) return;
    useForm({}).post(route('admin.invoices.mark-cancelled', invoice.id), { preserveScroll: true });
}

function resend(invoice: InvoiceItem) {
    useForm({}).post(route('admin.invoices.resend', invoice.id), { preserveScroll: true });
}

const STATUS_LABELS: Record<string, string> = { pending: 'En attente', paid: 'Payée', cancelled: 'Annulée' };
const STATUS_STYLES: Record<string, string> = {
    pending: 'bg-amber-50 text-amber-700',
    paid: 'bg-[#30998A]/10 text-[#1D5457]',
    cancelled: 'bg-red-50 text-red-600',
};

// ─── Création de facture manuelle ─────────────────────────────────────────────

const showCreateForm = ref(false);
const companies = ref<Company[]>([]);
const companySuggestions = ref<Company[]>([]);
const companySearch = ref('');

onMounted(async () => {
    const res = await fetch(route('admin.invoices.companies'));
    companies.value = await res.json();
});

watch(companySearch, (val) => {
    if (!val.trim()) { companySuggestions.value = []; return; }
    const q = val.toLowerCase();
    companySuggestions.value = companies.value.filter(
        c => c.name.toLowerCase().includes(q) || c.email.toLowerCase().includes(q)
    ).slice(0, 6);
});

interface LineItem {
    description: string;
    unit_price: string;
    years: string;
    discount: string;
}

const createForm = useForm({
    client_name: '',
    client_email: '',
    client_phone: '',
    client_company: '',
    client_bp: '',
    client_siege_social: '',
    client_address: '',
    save_company: false as boolean,
    items: [{ description: '', unit_price: '', years: '1', discount: '0' }] as LineItem[],
});

function fillFromCompany(company: Company) {
    createForm.client_email = company.email;
    createForm.client_company = company.name;
    createForm.client_phone = company.phone ?? '';
    createForm.client_bp = company.bp ?? '';
    createForm.client_siege_social = company.siege_social ?? '';
    createForm.client_address = company.address ?? '';
    companySearch.value = company.name;
    companySuggestions.value = [];
}

function addLine() {
    createForm.items.push({ description: '', unit_price: '', years: '1', discount: '0' });
}

function removeLine(index: number) {
    if (createForm.items.length > 1) createForm.items.splice(index, 1);
}

function lineTotal(item: LineItem): number {
    const p = parseInt(item.unit_price) || 0;
    const y = parseInt(item.years) || 1;
    const d = parseInt(item.discount) || 0;
    return Math.round(p * y * (100 - d) / 100);
}

const grandTotal = computed(() => createForm.items.reduce((sum, i) => sum + lineTotal(i), 0));

function submitCreate() {
    createForm.post(route('admin.invoices.store-manual'), {
        preserveScroll: true,
        onSuccess: () => {
            showCreateForm.value = false;
            createForm.reset();
            companySearch.value = '';
            // Rafraichir la liste des companies
            fetch(route('admin.invoices.companies')).then(r => r.json()).then(d => companies.value = d);
        },
    });
}
</script>

<template>
    <AppLayoutClient title="Factures">
        <Head title="Factures — Console admin" />

        <div class="px-4 py-8 sm:px-8">
            <!-- En-tête -->
            <div
                class="mb-8 flex flex-col justify-between gap-4 rounded-2xl px-6 py-6 text-white shadow-md sm:flex-row sm:items-center sm:px-8 sm:py-7"
                style="background: linear-gradient(135deg, #1D5457 0%, #0D2B29 100%);"
            >
                <div>
                    <p class="text-xs font-medium tracking-[0.25em] text-white/60">ADMINISTRATION</p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight">Factures</h1>
                    <p class="mt-1 text-sm text-white/70">Suivi des commandes et facturation manuelle.</p>
                </div>
                <div class="flex gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-xl bg-[#30998A] hover:bg-[#257A6E] px-5 py-3 text-sm font-semibold text-[#0A2422] transition-colors duration-200 cursor-pointer whitespace-nowrap"
                        @click="showCreateForm = !showCreateForm"
                    >
                        <Plus class="w-4 h-4 mr-1.5" /> Nouvelle facture
                    </button>
                    <Link
                        href="/admin"
                        class="inline-flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 px-5 py-3 text-sm font-semibold text-white transition-colors duration-200 cursor-pointer whitespace-nowrap"
                    >
                        ← Console admin
                    </Link>
                </div>
            </div>

            <p v-if="status" class="mb-6 rounded-xl border border-[#30998A]/30 bg-[#30998A]/10 px-4 py-3 text-sm text-[#1D5457]">
                {{ status }}
            </p>

            <!-- ─── Formulaire création manuelle ─────────────────────────────── -->
            <div v-if="showCreateForm" class="mb-8 rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-900">Nouvelle facture</h2>
                    <button type="button" class="text-slate-400 hover:text-slate-600 cursor-pointer" @click="showCreateForm = false; createForm.reset(); companySearch = ''">✕</button>
                </div>

                <form @submit.prevent="submitCreate" class="p-6 space-y-6">

                    <!-- Autocomplete entreprise -->
                    <div class="relative">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Rechercher une entreprise enregistrée</label>
                        <input
                            v-model="companySearch"
                            type="text"
                            placeholder="Nom ou email d'une entreprise déjà facturée..."
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20"
                        />
                        <div v-if="companySuggestions.length" class="absolute z-20 mt-1 w-full rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                            <button
                                v-for="c in companySuggestions"
                                :key="c.id"
                                type="button"
                                class="w-full text-left px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0 cursor-pointer"
                                @click="fillFromCompany(c)"
                            >
                                <span class="text-sm font-semibold text-slate-800">{{ c.name }}</span>
                                <span class="text-xs text-slate-500 ml-2">{{ c.email }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Infos client -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Informations client</label>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Nom du contact *</label>
                                <input v-model="createForm.client_name" type="text" required placeholder="Rodrigue Hervé Bayanak"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                                <p v-if="createForm.errors.client_name" class="mt-1 text-xs text-red-600">{{ createForm.errors.client_name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Email *</label>
                                <input v-model="createForm.client_email" type="email" required placeholder="contact@bhconnect.cm"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                                <p v-if="createForm.errors.client_email" class="mt-1 text-xs text-red-600">{{ createForm.errors.client_email }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Téléphone</label>
                                <input v-model="createForm.client_phone" type="text" placeholder="+237 6XX XXX XXX"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Entreprise / Raison sociale</label>
                                <input v-model="createForm.client_company" type="text" placeholder="BH CONNECT SARL"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">BP (Boîte Postale)</label>
                                <input v-model="createForm.client_bp" type="text" placeholder="BP 1234 Yaoundé"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Siège social</label>
                                <input v-model="createForm.client_siege_social" type="text" placeholder="Yaoundé, Cameroun"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Adresse complète</label>
                                <input v-model="createForm.client_address" type="text" placeholder="Quartier Bastos, Rue X, Yaoundé"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                            </div>
                        </div>

                        <label class="mt-4 flex items-center gap-2.5 cursor-pointer select-none">
                            <input v-model="createForm.save_company" type="checkbox" class="w-4 h-4 rounded accent-[#30998A]" />
                            <span class="text-sm text-slate-600">Enregistrer cette entreprise pour les prochaines factures</span>
                        </label>
                    </div>

                    <!-- Lignes de facturation -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Lignes de facturation *</label>
                            <button type="button" @click="addLine"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-[#1D5457] hover:text-[#30998A] cursor-pointer">
                                <Plus class="w-3.5 h-3.5" /> Ajouter une ligne
                            </button>
                        </div>

                        <!-- En-tête colonnes -->
                        <div class="hidden sm:grid grid-cols-[1fr_120px_60px_80px_90px_32px] gap-2 mb-2 px-1">
                            <span class="text-[10px] font-semibold text-slate-500 uppercase">Description</span>
                            <span class="text-[10px] font-semibold text-slate-500 uppercase">Prix unit./an (F)</span>
                            <span class="text-[10px] font-semibold text-slate-500 uppercase">Durée</span>
                            <span class="text-[10px] font-semibold text-slate-500 uppercase">Remise %</span>
                            <span class="text-[10px] font-semibold text-slate-500 uppercase text-right">Total</span>
                            <span></span>
                        </div>

                        <div v-for="(item, idx) in createForm.items" :key="idx"
                            class="grid sm:grid-cols-[1fr_120px_60px_80px_90px_32px] gap-2 mb-2 items-center">
                            <input v-model="item.description" type="text" required placeholder="VPS Basic Unmanaged"
                                class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                            <input v-model="item.unit_price" type="number" min="0" step="500" required placeholder="62500"
                                class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                            <input v-model="item.years" type="number" min="1" max="10" required placeholder="1"
                                class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                            <input v-model="item.discount" type="number" min="0" max="100" required placeholder="0"
                                class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20" />
                            <div class="text-sm font-semibold text-slate-800 text-right pr-1">
                                {{ fmt(lineTotal(item)) }} F
                            </div>
                            <button type="button" :disabled="createForm.items.length === 1" @click="removeLine(idx)"
                                class="flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer transition-colors">
                                <Trash2 class="w-3.5 h-3.5" />
                            </button>
                        </div>

                        <p v-if="createForm.errors['items']" class="mt-1 text-xs text-red-600">{{ createForm.errors['items'] }}</p>

                        <!-- Total -->
                        <div class="mt-3 flex justify-end">
                            <div class="rounded-xl bg-[#0D2B29] px-5 py-3 text-right">
                                <span class="text-xs text-white/60 mr-3">TOTAL À PAYER</span>
                                <span class="text-lg font-extrabold text-white">{{ fmt(grandTotal) }} F CFA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-2 border-t border-slate-100">
                        <button type="submit" :disabled="createForm.processing"
                            class="inline-flex items-center rounded-xl bg-[#1D5457] hover:bg-[#164042] disabled:opacity-60 px-6 py-2.5 text-sm font-semibold text-white transition-colors duration-200 cursor-pointer">
                            {{ createForm.processing ? 'Création en cours...' : 'Créer & envoyer la facture' }}
                        </button>
                        <button type="button"
                            class="inline-flex items-center rounded-xl border border-slate-200 hover:bg-slate-50 px-6 py-2.5 text-sm font-semibold text-slate-600 transition-colors duration-200 cursor-pointer"
                            @click="showCreateForm = false; createForm.reset(); companySearch = ''">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>

            <!-- ─── Liste des factures ──────────────────────────────────────── -->
            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-900">Factures ({{ invoices.length }})</h2>
                </div>

                <div v-if="!invoices.length" class="px-6 py-8 text-center text-sm text-slate-500">Aucune facture pour le moment.</div>

                <div v-for="invoice in invoices" :key="invoice.id" class="px-6 py-5 border-b border-slate-50 last:border-0">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">
                                {{ invoice.invoice_number }} — {{ invoice.item_name }}
                                <span class="text-slate-400 font-normal">({{ invoice.years }} an{{ invoice.years > 1 ? 's' : '' }})</span>
                            </p>
                            <p class="text-xs text-slate-500">{{ invoice.client_name }} · {{ invoice.client_email }} · {{ invoice.created_at }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-slate-900">{{ fmt(invoice.total_amount) }} F</span>
                            <span :class="['text-[10px] font-semibold uppercase tracking-wide rounded-full px-2 py-0.5', STATUS_STYLES[invoice.status]]">
                                {{ STATUS_LABELS[invoice.status] }}
                            </span>

                            <a :href="route('admin.invoices.download', invoice.id)" target="_blank"
                                class="text-xs font-semibold text-[#1D5457] hover:underline cursor-pointer">Télécharger</a>
                            <button type="button" class="text-xs font-semibold text-slate-500 hover:underline cursor-pointer" @click="resend(invoice)">Renvoyer</button>
                            <button v-if="invoice.status === 'pending'" type="button"
                                class="text-xs font-semibold text-[#1D5457] hover:underline cursor-pointer" @click="markPaid(invoice)">
                                Marquer payée
                            </button>
                            <button v-if="invoice.status === 'pending'" type="button"
                                class="text-xs font-semibold text-red-600 hover:underline cursor-pointer" @click="markCancelled(invoice)">
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayoutClient>
</template>
