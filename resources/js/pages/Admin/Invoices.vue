<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import { Plus, Trash2, Pencil, X } from 'lucide-vue-next';

interface LineItem {
    id?: number;
    description: string;
    unit_price: string;
    years: string;
    discount: string;
}

interface FullInvoice {
    id: number;
    invoice_number: string;
    client_name: string;
    client_email: string;
    client_phone: string | null;
    client_company: string | null;
    client_bp: string | null;
    client_siege_social: string | null;
    client_address: string | null;
    item_name: string;
    years: number;
    total_amount: number;
    status: 'pending' | 'paid' | 'cancelled';
    created_at: string;
    items: { id: number; description: string; unit_price: number; years: number; discount_percent: number; line_total: number }[];
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

const props = defineProps<{ invoices: FullInvoice[]; status?: string | null }>();

function fmt(n: number) { return new Intl.NumberFormat('fr-FR').format(n); }

const STATUS_LABELS: Record<string, string> = { pending: 'En attente', paid: 'Payée', cancelled: 'Annulée' };
const STATUS_STYLES: Record<string, string> = {
    pending: 'bg-amber-50 text-amber-700 border border-amber-200',
    paid: 'bg-[#30998A]/10 text-[#1D5457] border border-[#30998A]/20',
    cancelled: 'bg-red-50 text-red-600 border border-red-200',
};
const STATUS_EXPLANATIONS: Record<string, string> = {
    pending: 'En attente de paiement du client',
    paid: 'Paiement reçu et confirmé',
    cancelled: 'Facture annulée',
};

// ─── Actions rapides ────────────────────────────────────────────────
function markPaid(invoice: FullInvoice) {
    if (!confirm(`Marquer la facture ${invoice.invoice_number} comme payée ?`)) return;
    useForm({}).post(route('admin.invoices.mark-paid', invoice.id), { preserveScroll: true });
}
function markCancelled(invoice: FullInvoice) {
    if (!confirm(`Annuler la facture ${invoice.invoice_number} ?`)) return;
    useForm({}).post(route('admin.invoices.mark-cancelled', invoice.id), { preserveScroll: true });
}
function resend(invoice: FullInvoice) {
    useForm({}).post(route('admin.invoices.resend', invoice.id), { preserveScroll: true });
}

// ─── Création ───────────────────────────────────────────────────────
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

function addLine(form: typeof createForm | typeof editForm) {
    form.items.push({ description: '', unit_price: '', years: '1', discount: '0' });
}
function removeLine(form: typeof createForm | typeof editForm, index: number) {
    if (form.items.length > 1) form.items.splice(index, 1);
}

function lineTotal(item: LineItem): number {
    const p = parseInt(item.unit_price as string) || 0;
    const y = parseInt(item.years as string) || 1;
    const d = parseInt(item.discount as string) || 0;
    return Math.round(p * y * (100 - d) / 100);
}
const grandTotal = computed(() => createForm.items.reduce((s, i) => s + lineTotal(i), 0));

function submitCreate() {
    createForm.post(route('admin.invoices.store-manual'), {
        preserveScroll: true,
        onSuccess: () => {
            showCreateForm.value = false;
            createForm.reset();
            companySearch.value = '';
            fetch(route('admin.invoices.companies')).then(r => r.json()).then(d => companies.value = d);
        },
    });
}

// ─── Édition ────────────────────────────────────────────────────────
const editingId = ref<number | null>(null);
const editForm = useForm({
    client_name: '',
    client_email: '',
    client_phone: '',
    client_company: '',
    client_bp: '',
    client_siege_social: '',
    client_address: '',
    status: 'pending' as 'pending' | 'paid' | 'cancelled',
    items: [] as LineItem[],
});

function openEdit(invoice: FullInvoice) {
    editingId.value = invoice.id;
    editForm.client_name = invoice.client_name;
    editForm.client_email = invoice.client_email;
    editForm.client_phone = invoice.client_phone ?? '';
    editForm.client_company = invoice.client_company ?? '';
    editForm.client_bp = invoice.client_bp ?? '';
    editForm.client_siege_social = invoice.client_siege_social ?? '';
    editForm.client_address = invoice.client_address ?? '';
    editForm.status = invoice.status;

    if (invoice.items && invoice.items.length > 0) {
        editForm.items = invoice.items.map(it => ({
            description: it.description,
            unit_price: String(it.unit_price),
            years: String(it.years),
            discount: String(it.discount_percent),
        }));
    } else {
        editForm.items = [{ description: invoice.item_name, unit_price: String(invoice.total_amount), years: '1', discount: '0' }];
    }
}

function closeEdit() { editingId.value = null; }

const editGrandTotal = computed(() => editForm.items.reduce((s, i) => s + lineTotal(i), 0));

function submitEdit(invoice: FullInvoice) {
    editForm.put(route('admin.invoices.update', invoice.id), {
        preserveScroll: true,
        onSuccess: () => closeEdit(),
    });
}

// ─── Shared field classes ────────────────────────────────────────────
const fieldClass = 'w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20';
const smallFieldClass = 'w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20';
</script>

<template>
    <AppLayoutClient title="Factures">
        <Head title="Factures — Console admin" />

        <div class="px-4 py-8 sm:px-8">
            <!-- En-tête -->
            <div class="mb-8 flex flex-col justify-between gap-4 rounded-2xl px-6 py-6 text-white shadow-md sm:flex-row sm:items-center sm:px-8 sm:py-7"
                style="background: linear-gradient(135deg, #1D5457 0%, #0D2B29 100%);">
                <div>
                    <p class="text-xs font-medium tracking-[0.25em] text-white/60">ADMINISTRATION</p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight">Factures</h1>
                    <p class="mt-1 text-sm text-white/70">Suivi des commandes et facturation manuelle.</p>
                </div>
                <div class="flex gap-3">
                    <button type="button"
                        class="inline-flex items-center justify-center rounded-xl bg-[#30998A] hover:bg-[#257A6E] px-5 py-3 text-sm font-semibold text-[#0A2422] transition-colors duration-200 cursor-pointer whitespace-nowrap"
                        @click="showCreateForm = !showCreateForm; editingId = null">
                        <Plus class="w-4 h-4 mr-1.5" /> Nouvelle facture
                    </button>
                    <Link href="/admin"
                        class="inline-flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 px-5 py-3 text-sm font-semibold text-white transition-colors duration-200 cursor-pointer whitespace-nowrap">
                        ← Console admin
                    </Link>
                </div>
            </div>

            <p v-if="status" class="mb-6 rounded-xl border border-[#30998A]/30 bg-[#30998A]/10 px-4 py-3 text-sm text-[#1D5457]">
                {{ status }}
            </p>

            <!-- ─── Formulaire CRÉATION ──────────────────────────────────── -->
            <div v-if="showCreateForm" class="mb-8 rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-900">Nouvelle facture</h2>
                    <button type="button" class="text-slate-400 hover:text-slate-600 cursor-pointer"
                        @click="showCreateForm = false; createForm.reset(); companySearch = ''">
                        <X class="w-4 h-4" />
                    </button>
                </div>
                <form @submit.prevent="submitCreate" class="p-6 space-y-6">
                    <!-- Autocomplete -->
                    <div class="relative">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Rechercher une entreprise enregistrée</label>
                        <input v-model="companySearch" type="text" placeholder="Nom ou email d'une entreprise déjà facturée..." :class="fieldClass" />
                        <div v-if="companySuggestions.length" class="absolute z-20 mt-1 w-full rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                            <button v-for="c in companySuggestions" :key="c.id" type="button"
                                class="w-full text-left px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0 cursor-pointer"
                                @click="fillFromCompany(c)">
                                <span class="text-sm font-semibold text-slate-800">{{ c.name }}</span>
                                <span class="text-xs text-slate-500 ml-2">{{ c.email }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Champs client -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Informations client</label>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Nom du contact *</label>
                                <input v-model="createForm.client_name" type="text" required placeholder="Rodrigue Hervé Bayanak" :class="fieldClass" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Email *</label>
                                <input v-model="createForm.client_email" type="email" required placeholder="contact@bhconnect.cm" :class="fieldClass" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Téléphone</label>
                                <input v-model="createForm.client_phone" type="text" placeholder="+237 6XX XXX XXX" :class="fieldClass" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Entreprise / Raison sociale</label>
                                <input v-model="createForm.client_company" type="text" placeholder="BH CONNECT SARL" :class="fieldClass" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">BP</label>
                                <input v-model="createForm.client_bp" type="text" placeholder="BP 1234 Yaoundé" :class="fieldClass" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Siège social</label>
                                <input v-model="createForm.client_siege_social" type="text" placeholder="Yaoundé, Cameroun" :class="fieldClass" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Adresse complète</label>
                                <input v-model="createForm.client_address" type="text" placeholder="Quartier Bastos, Rue X, Yaoundé" :class="fieldClass" />
                            </div>
                        </div>
                        <label class="mt-4 flex items-center gap-2.5 cursor-pointer select-none">
                            <input v-model="createForm.save_company" type="checkbox" class="w-4 h-4 rounded accent-[#30998A]" />
                            <span class="text-sm text-slate-600">Enregistrer cette entreprise pour les prochaines factures</span>
                        </label>
                    </div>

                    <!-- Lignes -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Lignes de facturation *</label>
                            <button type="button" @click="addLine(createForm)"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-[#1D5457] hover:text-[#30998A] cursor-pointer">
                                <Plus class="w-3.5 h-3.5" /> Ajouter une ligne
                            </button>
                        </div>
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
                            <input v-model="item.description" type="text" required placeholder="VPS Basic Unmanaged" :class="smallFieldClass" />
                            <input v-model="item.unit_price" type="number" min="0" step="500" required placeholder="62500" :class="smallFieldClass" />
                            <input v-model="item.years" type="number" min="1" max="10" required :class="smallFieldClass" />
                            <input v-model="item.discount" type="number" min="0" max="100" required :class="smallFieldClass" />
                            <div class="text-sm font-semibold text-slate-800 text-right pr-1">{{ fmt(lineTotal(item)) }} F</div>
                            <button type="button" :disabled="createForm.items.length === 1" @click="removeLine(createForm, idx)"
                                class="flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer transition-colors">
                                <Trash2 class="w-3.5 h-3.5" />
                            </button>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <div class="rounded-xl bg-[#0D2B29] px-5 py-3 text-right">
                                <span class="text-xs text-white/60 mr-3">TOTAL À PAYER</span>
                                <span class="text-lg font-extrabold text-white">{{ fmt(grandTotal) }} F CFA</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2 border-t border-slate-100">
                        <button type="submit" :disabled="createForm.processing"
                            class="inline-flex items-center rounded-xl bg-[#1D5457] hover:bg-[#164042] disabled:opacity-60 px-6 py-2.5 text-sm font-semibold text-white transition-colors cursor-pointer">
                            {{ createForm.processing ? 'Création...' : 'Créer & envoyer la facture' }}
                        </button>
                        <button type="button"
                            class="inline-flex items-center rounded-xl border border-slate-200 hover:bg-slate-50 px-6 py-2.5 text-sm font-semibold text-slate-600 transition-colors cursor-pointer"
                            @click="showCreateForm = false; createForm.reset(); companySearch = ''">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>

            <!-- ─── Liste des factures ─────────────────────────────────────── -->
            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-900">Factures ({{ invoices.length }})</h2>
                </div>

                <div v-if="!invoices.length" class="px-6 py-8 text-center text-sm text-slate-500">Aucune facture pour le moment.</div>

                <template v-for="invoice in invoices" :key="invoice.id">
                    <!-- Ligne résumé -->
                    <div class="px-6 py-5 border-b border-slate-50">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ invoice.invoice_number }} — {{ invoice.item_name }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ invoice.client_name }}
                                    <span v-if="invoice.client_company"> · {{ invoice.client_company }}</span>
                                    · {{ invoice.client_email }} · {{ invoice.created_at }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="text-sm font-bold text-slate-900">{{ fmt(invoice.total_amount) }} F</span>

                                <!-- Badge statut avec explication au survol -->
                                <span :title="STATUS_EXPLANATIONS[invoice.status]"
                                    :class="['text-[10px] font-semibold uppercase tracking-wide rounded-full px-2.5 py-1 cursor-default', STATUS_STYLES[invoice.status]]">
                                    {{ STATUS_LABELS[invoice.status] }}
                                </span>

                                <button type="button" @click="editingId === invoice.id ? closeEdit() : openEdit(invoice)"
                                    :class="['inline-flex items-center gap-1 text-xs font-semibold transition-colors cursor-pointer',
                                        editingId === invoice.id ? 'text-slate-400 hover:text-slate-600' : 'text-[#1D5457] hover:text-[#30998A]']">
                                    <Pencil v-if="editingId !== invoice.id" class="w-3 h-3" />
                                    <X v-else class="w-3 h-3" />
                                    {{ editingId === invoice.id ? 'Fermer' : 'Modifier' }}
                                </button>

                                <a :href="route('admin.invoices.download', invoice.id)" target="_blank"
                                    class="text-xs font-semibold text-[#1D5457] hover:underline cursor-pointer">Télécharger</a>
                                <button type="button" class="text-xs font-semibold text-slate-500 hover:underline cursor-pointer" @click="resend(invoice)">Renvoyer</button>
                                <button v-if="invoice.status === 'pending'" type="button"
                                    class="text-xs font-semibold text-[#1D5457] hover:underline cursor-pointer" @click="markPaid(invoice)">
                                    Marquer payée
                                </button>
                                <button v-if="invoice.status === 'pending'" type="button"
                                    class="text-xs font-semibold text-red-500 hover:underline cursor-pointer" @click="markCancelled(invoice)">
                                    Annuler
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Panneau ÉDITION inline -->
                    <div v-if="editingId === invoice.id" class="border-b border-slate-100 bg-slate-50/70">
                        <form @submit.prevent="submitEdit(invoice)" class="p-6 space-y-5">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Modifier la facture {{ invoice.invoice_number }}</h3>

                            <!-- Infos client -->
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Nom du contact *</label>
                                    <input v-model="editForm.client_name" type="text" required :class="fieldClass" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Email *</label>
                                    <input v-model="editForm.client_email" type="email" required :class="fieldClass" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Téléphone</label>
                                    <input v-model="editForm.client_phone" type="text" :class="fieldClass" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Entreprise</label>
                                    <input v-model="editForm.client_company" type="text" :class="fieldClass" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">BP</label>
                                    <input v-model="editForm.client_bp" type="text" :class="fieldClass" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Siège social</label>
                                    <input v-model="editForm.client_siege_social" type="text" :class="fieldClass" />
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Adresse</label>
                                    <input v-model="editForm.client_address" type="text" :class="fieldClass" />
                                </div>
                            </div>

                            <!-- Statut -->
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Statut</label>
                                <select v-model="editForm.status"
                                    class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20 cursor-pointer">
                                    <option value="pending">En attente (paiement non reçu)</option>
                                    <option value="paid">Payée (paiement confirmé)</option>
                                    <option value="cancelled">Annulée</option>
                                </select>
                            </div>

                            <!-- Lignes -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Lignes de facturation</label>
                                    <button type="button" @click="addLine(editForm)"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-[#1D5457] hover:text-[#30998A] cursor-pointer">
                                        <Plus class="w-3.5 h-3.5" /> Ajouter une ligne
                                    </button>
                                </div>
                                <div class="hidden sm:grid grid-cols-[1fr_120px_60px_80px_90px_32px] gap-2 mb-2 px-1">
                                    <span class="text-[10px] font-semibold text-slate-500 uppercase">Description</span>
                                    <span class="text-[10px] font-semibold text-slate-500 uppercase">Prix unit./an (F)</span>
                                    <span class="text-[10px] font-semibold text-slate-500 uppercase">Durée</span>
                                    <span class="text-[10px] font-semibold text-slate-500 uppercase">Remise %</span>
                                    <span class="text-[10px] font-semibold text-slate-500 uppercase text-right">Total</span>
                                    <span></span>
                                </div>
                                <div v-for="(item, idx) in editForm.items" :key="idx"
                                    class="grid sm:grid-cols-[1fr_120px_60px_80px_90px_32px] gap-2 mb-2 items-center">
                                    <input v-model="item.description" type="text" required :class="smallFieldClass" />
                                    <input v-model="item.unit_price" type="number" min="0" step="500" required :class="smallFieldClass" />
                                    <input v-model="item.years" type="number" min="1" max="10" required :class="smallFieldClass" />
                                    <input v-model="item.discount" type="number" min="0" max="100" required :class="smallFieldClass" />
                                    <div class="text-sm font-semibold text-slate-800 text-right pr-1">{{ fmt(lineTotal(item)) }} F</div>
                                    <button type="button" :disabled="editForm.items.length === 1" @click="removeLine(editForm, idx)"
                                        class="flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer transition-colors">
                                        <Trash2 class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <div class="rounded-xl bg-[#0D2B29] px-5 py-3 text-right">
                                        <span class="text-xs text-white/60 mr-3">TOTAL À PAYER</span>
                                        <span class="text-lg font-extrabold text-white">{{ fmt(editGrandTotal) }} F CFA</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3 pt-2 border-t border-slate-200">
                                <button type="submit" :disabled="editForm.processing"
                                    class="inline-flex items-center rounded-xl bg-[#1D5457] hover:bg-[#164042] disabled:opacity-60 px-6 py-2.5 text-sm font-semibold text-white transition-colors cursor-pointer">
                                    {{ editForm.processing ? 'Enregistrement...' : 'Enregistrer les modifications' }}
                                </button>
                                <button type="button" @click="closeEdit"
                                    class="inline-flex items-center rounded-xl border border-slate-200 hover:bg-slate-100 px-6 py-2.5 text-sm font-semibold text-slate-600 transition-colors cursor-pointer">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </template>
            </div>
        </div>
    </AppLayoutClient>
</template>
