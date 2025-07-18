<template>
  <AppLayout>
    <!-- Hero Contact -->
    <section class="relative bg-diginova-blue py-20 text-white">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl text-center">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6">
          Contactez notre équipe
        </h1>
        <p class="text-xl sm:text-2xl max-w-3xl mx-auto opacity-90">
          Nous sommes là pour répondre à vos questions et vous accompagner dans vos projets digitaux.
        </p>
      </div>
    </section>

    <!-- Main Contact Section -->
    <section class="py-16 sm:py-24 bg-gray-50 font-diginova">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <!-- Contact Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
          <!-- Card 1 - Adresse -->
          <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 hover:border-diginova-red/30 transition-all duration-300 hover:-translate-y-2">
            <div class="w-14 h-14 bg-diginova-red/10 rounded-full flex items-center justify-center text-diginova-red mb-6">
              <i class="bi bi-geo-alt text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-diginova-blue mb-3">Nos bureaux</h3>
            <p class="text-gray-600 mb-4">123 Rue de l'Innovation<br>75000 Paris, France</p>
            <a href="https://maps.google.com" target="_blank" class="inline-flex items-center text-diginova-red font-medium hover:text-diginova-blue transition-colors">
              Voir sur la carte <i class="bi bi-arrow-up-right ml-1"></i>
            </a>
          </div>

          <!-- Card 2 - Support -->
          <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 hover:border-diginova-red/30 transition-all duration-300 hover:-translate-y-2">
            <div class="w-14 h-14 bg-diginova-red/10 rounded-full flex items-center justify-center text-diginova-red mb-6">
              <i class="bi bi-headset text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-diginova-blue mb-3">Support client</h3>
            <p class="text-gray-600 mb-2">
              <a href="tel:+33123456789" class="hover:text-diginova-red transition-colors">+33 1 23 45 67 89</a>
            </p>
            <p class="text-gray-600 mb-4">
              <a href="mailto:support@diginova.fr" class="hover:text-diginova-red transition-colors">support@diginova.fr</a>
            </p>
            <p class="text-sm text-gray-500">Lun-Ven : 9h-18h</p>
          </div>

          <!-- Card 3 - Chat -->
          <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 hover:border-diginova-red/30 transition-all duration-300 hover:-translate-y-2">
            <div class="w-14 h-14 bg-diginova-red/10 rounded-full flex items-center justify-center text-diginova-red mb-6">
              <i class="bi bi-chat-dots text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-diginova-blue mb-3">Chat en direct</h3>
            <p class="text-gray-600 mb-4">Discutez en direct avec notre équipe pendant les heures d'ouverture.</p>
            <button @click="openChat" class="px-4 py-2 bg-diginova-red text-white rounded-lg hover:bg-diginova-blue transition-colors flex items-center">
              <i class="bi bi-chat-square-text mr-2"></i> Démarrer le chat
            </button>
          </div>
        </div>

        <!-- Contact Form Section -->
        <div class="grid lg:grid-cols-2 gap-12">
          <!-- Left Side - Info -->
          <div class="space-y-8">
            <div>
              <h2 class="text-3xl font-bold text-diginova-blue mb-4">Travaillons ensemble</h2>
              <p class="text-lg text-gray-600">
                Que vous ayez une question sur nos services, besoin d'un devis ou simplement envie de discuter de votre projet, notre équipe est à votre écoute.
              </p>
            </div>

            <div class="space-y-6">
              <div class="flex items-start">
                <div class="flex-shrink-0 bg-diginova-red/10 p-3 rounded-lg text-diginova-red">
                  <i class="bi bi-lightning-charge text-xl"></i>
                </div>
                <div class="ml-4">
                  <h4 class="font-bold text-diginova-blue">Temps de réponse rapide</h4>
                  <p class="text-gray-600 mt-1">Nous nous engageons à vous répondre dans les 24 heures ouvrables.</p>
                </div>
              </div>

              <div class="flex items-start">
                <div class="flex-shrink-0 bg-diginova-red/10 p-3 rounded-lg text-diginova-red">
                  <i class="bi bi-shield-check text-xl"></i>
                </div>
                <div class="ml-4">
                  <h4 class="font-bold text-diginova-blue">Confidentialité garantie</h4>
                  <p class="text-gray-600 mt-1">Vos informations sont sécurisées et ne seront jamais partagées.</p>
                </div>
              </div>

              <div class="flex items-start">
                <div class="flex-shrink-0 bg-diginova-red/10 p-3 rounded-lg text-diginova-red">
                  <i class="bi bi-people text-xl"></i>
                </div>
                <div class="ml-4">
                  <h4 class="font-bold text-diginova-blue">Équipe dédiée</h4>
                  <p class="text-gray-600 mt-1">Un expert vous accompagne personnellement tout au long de votre projet.</p>
                </div>
              </div>
            </div>

            <div class="pt-4">
              <h4 class="font-bold text-diginova-blue mb-4">Suivez-nous</h4>
              <div class="flex space-x-4">
                <a v-for="(social, index) in socialMedia" :key="index" :href="social.url" target="_blank" class="w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm text-diginova-blue hover:bg-diginova-red hover:text-white transition-colors">
                  <i class="bi" :class="social.icon"></i>
                </a>
              </div>
            </div>
          </div>

          <!-- Right Side - Form -->
          <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 border border-gray-100">
            <h3 class="text-2xl font-bold text-diginova-blue mb-2">Envoyez-nous un message</h3>
            <p class="text-gray-500 mb-8">Les champs marqués d'un * sont obligatoires</p>
            
            <form @submit.prevent="submitContactForm" class="space-y-6">
              <!-- Input Prénom -->
              <div class="relative">
                <input 
                  id="contact-firstname"
                  v-model="contactForm.firstName"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-diginova-red focus:border-diginova-red peer"
                  placeholder=" "
                  required
                />
                <label 
                  for="contact-firstname"
                  class="absolute left-4 top-3 px-1 bg-white text-gray-500 transition-all duration-200 pointer-events-none
                         peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                         peer-focus:-top-2 peer-focus:text-sm peer-focus:text-diginova-red"
                >
                  Prénom *
                </label>
              </div>

              <!-- Input Nom -->
              <div class="relative">
                <input 
                  id="contact-lastname"
                  v-model="contactForm.lastName"
                  type="text"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-diginova-red focus:border-diginova-red peer"
                  placeholder=" "
                  required
                />
                <label 
                  for="contact-lastname"
                  class="absolute left-4 top-3 px-1 bg-white text-gray-500 transition-all duration-200 pointer-events-none
                         peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                         peer-focus:-top-2 peer-focus:text-sm peer-focus:text-diginova-red"
                >
                  Nom *
                </label>
              </div>

              <!-- Input Email -->
              <div class="relative">
                <input 
                  id="contact-email"
                  v-model="contactForm.email"
                  type="email"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-diginova-red focus:border-diginova-red peer"
                  placeholder=" "
                  required
                />
                <label 
                  for="contact-email"
                  class="absolute left-4 top-3 px-1 bg-white text-gray-500 transition-all duration-200 pointer-events-none
                         peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                         peer-focus:-top-2 peer-focus:text-sm peer-focus:text-diginova-red"
                >
                  Email *
                </label>
              </div>

              <!-- Input Téléphone -->
              <div class="relative">
                <input 
                  id="contact-phone"
                  v-model="contactForm.phone"
                  type="tel"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-diginova-red focus:border-diginova-red peer"
                  placeholder=" "
                />
                <label 
                  for="contact-phone"
                  class="absolute left-4 top-3 px-1 bg-white text-gray-500 transition-all duration-200 pointer-events-none
                         peer-placeholder-shown:top-3 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                         peer-focus:-top-2 peer-focus:text-sm peer-focus:text-diginova-red"
                >
                  Téléphone
                </label>
              </div>

              <!-- Select Sujet -->
              <div>
                <label class="block text-sm font-medium text-diginova-blue mb-2">Sujet *</label>
                <div class="relative">
                  <select 
                    id="contact-subject" 
                    v-model="contactForm.subject"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-diginova-red focus:border-diginova-red appearance-none outline-none"
                    required
                  >
                    <option value="" disabled selected>Sélectionnez un sujet</option>
                    <option value="quote">Demande de devis</option>
                    <option value="support">Support technique</option>
                    <option value="partnership">Partenariat</option>
                    <option value="recruitment">Recrutement</option>
                    <option value="other">Autre</option>
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <i class="bi bi-chevron-down"></i>
                  </div>
                </div>
              </div>

              <!-- Textarea Message -->
              <div>
                <label class="block text-sm font-medium text-diginova-blue mb-2">Message *</label>
                <textarea 
                  id="contact-message" 
                  v-model="contactForm.message"
                  rows="5"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-diginova-red focus:border-diginova-red outline-none"
                  placeholder="Décrivez votre demande en détail..."
                  required
                ></textarea>
              </div>

              <!-- Checkbox Newsletter -->
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input 
                    id="contact-newsletter"
                    v-model="contactForm.newsletter"
                    type="checkbox" 
                    class="h-4 w-4 text-diginova-red focus:ring-diginova-red border-gray-300 rounded"
                  >
                </div>
                <div class="ml-3 text-sm">
                  <label for="contact-newsletter" class="text-gray-600">
                    Je souhaite recevoir la newsletter Diginova (conseils, offres spéciales)
                  </label>
                </div>
              </div>

              <!-- Bouton Submit -->
              <div class="pt-2">
                <button
                  type="submit"
                  class="w-full px-6 py-4 bg-diginova-red text-white rounded-lg hover:bg-diginova-blue transition-colors shadow-lg flex items-center justify-center"
                  :disabled="contactSubmitting"
                  :class="{ 'opacity-70 cursor-not-allowed': contactSubmitting }"
                >
                  <span v-if="contactSubmitting">
                    <i class="bi bi-arrow-repeat animate-spin mr-2"></i> Envoi en cours...
                  </span>
                  <span v-else class="flex items-center">
                    <i class="bi bi-send-check mr-2"></i> Envoyer le message
                  </span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
        <h2 class="text-3xl font-bold text-center text-diginova-blue mb-12">Questions fréquentes</h2>
        
        <div class="space-y-4">
          <div v-for="(item, index) in faqs" :key="index" class="border-b border-gray-200 pb-4">
            <button 
              @click="toggleFaq(index)"
              class="flex justify-between items-center w-full text-left font-medium text-diginova-blue hover:text-diginova-red transition-colors"
            >
              <span>{{ item.question }}</span>
              <i class="bi" :class="openedFaq === index ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
            <div v-show="openedFaq === index" class="mt-2 text-gray-600">
              {{ item.answer }}
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Chat Widget -->
    <div 
      v-if="showChat"
      class="fixed bottom-8 right-8 w-96 bg-white rounded-2xl shadow-2xl overflow-hidden z-50 border border-gray-200"
    >
      <div class="bg-diginova-red p-4 text-white flex justify-between items-center">
        <h3 class="font-bold flex items-center">
          <i class="bi bi-chat-square-text-fill mr-2"></i> Chat en direct
        </h3>
        <button @click="closeChat" class="text-white hover:text-gray-200">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
      <div class="p-4 h-80 overflow-y-auto">
        <div v-if="chatMessages.length === 0" class="text-center text-gray-500 h-full flex flex-col justify-center">
          <i class="bi bi-chat-left-text text-4xl mb-2"></i>
          <p>Bonjour ! Comment pouvons-nous vous aider aujourd'hui ?</p>
        </div>
        <div v-else class="space-y-4">
          <div 
            v-for="(msg, index) in chatMessages" 
            :key="index"
            class="flex"
            :class="{ 'justify-end': msg.sender === 'user' }"
          >
            <div 
              class="max-w-xs p-3 rounded-lg"
              :class="{
                'bg-gray-100': msg.sender === 'agent',
                'bg-diginova-red/10 text-diginova-blue': msg.sender === 'user'
              }"
            >
              {{ msg.text }}
            </div>
          </div>
        </div>
      </div>
      <div class="border-t p-4">
        <div class="flex">
          <input 
            v-model="chatMessage"
            @keyup.enter="sendChatMessage"
            type="text" 
            placeholder="Tapez votre message..."
            class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-diginova-red focus:border-diginova-red outline-none"
          >
          <button 
            @click="sendChatMessage"
            class="px-4 py-2 bg-diginova-red text-white rounded-r-lg hover:bg-diginova-blue transition-colors"
          >
            <i class="bi bi-send"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Chat Button -->
    <button 
      v-if="!showChat"
      @click="openChat"
      class="fixed bottom-8 right-8 w-14 h-14 bg-diginova-red text-white rounded-full shadow-lg flex items-center justify-center hover:bg-diginova-blue transition-all z-40"
      aria-label="Ouvrir le chat"
    >
      <i class="bi bi-chat-square-text-fill text-xl"></i>
    </button>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayoutPublic.vue'

// Social Media Links
const socialMedia = ref([
  { name: 'Facebook', icon: 'bi-facebook', url: '#' },
  { name: 'Twitter', icon: 'bi-twitter-x', url: '#' },
  { name: 'LinkedIn', icon: 'bi-linkedin', url: '#' },
  { name: 'Instagram', icon: 'bi-instagram', url: '#' },
  { name: 'YouTube', icon: 'bi-youtube', url: '#' }
])

// FAQ Data
const faqs = ref([
  {
    question: "Quel est votre délai de réponse moyen ?",
    answer: "Nous nous engageons à répondre à toutes les demandes dans un délai de 24 heures ouvrables. Pour les demandes urgentes, vous pouvez nous appeler directement au +33 1 23 45 67 89."
  },
  {
    question: "Quelles sont vos heures d'ouverture ?",
    answer: "Notre équipe est disponible du lundi au vendredi de 9h à 18h. Le service de chat en direct est actif de 8h à 20h en semaine."
  },
  {
    question: "Comment puis-je suivre ma demande ?",
    answer: "Dès réception de votre demande, vous recevrez un email de confirmation avec un numéro de suivi. Vous pouvez également nous contacter à tout moment pour avoir des nouvelles de votre demande."
  },
  {
    question: "Proposez-vous des consultations gratuites ?",
    answer: "Oui, nous offrons une première consultation gratuite de 30 minutes pour discuter de votre projet et voir comment nous pouvons vous aider."
  }
])

// Chat Functionality
const showChat = ref(false)
const chatMessage = ref('')
const chatMessages = ref([])

const openChat = () => {
  showChat.value = true
}

const closeChat = () => {
  showChat.value = false
}

const sendChatMessage = () => {
  if (chatMessage.value.trim() === '') return
  
  // Add user message
  chatMessages.value.push({
    sender: 'user',
    text: chatMessage.value
  })
  
  // Simulate agent response
  setTimeout(() => {
    chatMessages.value.push({
      sender: 'agent',
      text: "Merci pour votre message. Un de nos conseillers vous répondra dans quelques instants."
    })
  }, 1000)
  
  chatMessage.value = ''
}

// FAQ Toggle
const openedFaq = ref(null)

const toggleFaq = (index) => {
  openedFaq.value = openedFaq.value === index ? null : index
}

// Contact Form
const contactForm = ref({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  subject: '',
  message: '',
  newsletter: false
})

const contactSubmitting = ref(false)

const submitContactForm = async () => {
  contactSubmitting.value = true
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // Reset form
    contactForm.value = {
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      subject: '',
      message: '',
      newsletter: false
    }
    
    // Show success message
    alert('Merci pour votre message ! Nous vous répondrons dans les plus brefs délais.')
    
  } catch (error) {
    console.error('Error submitting form:', error)
    alert("Une erreur s'est produite. Veuillez réessayer plus tard.")
  } finally {
    contactSubmitting.value = false
  }
}
</script>

<style scoped>
/* Floating Label Effect */
.relative:focus-within label {
  top: -0.5rem;
  font-size: 0.875rem;
  color: #E31937;
  background-color: white;
  padding: 0 0.25rem;
}

.relative label {
  position: absolute;
  left: 1rem;
  top: 0.75rem;
  padding: 0 0.25rem;
  background-color: white;
  transition: all 0.2s ease;
  font-size: 1rem;
  color: #6b7280;
  pointer-events: none;
}

/* Custom select arrow */
select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23E31937'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 16px 12px;
}

/* Animation for FAQ */
.faq-enter-active,
.faq-leave-active {
  transition: all 0.3s ease;
  max-height: 500px;
}

.faq-enter-from,
.faq-leave-to {
  opacity: 0;
  max-height: 0;
  transform: translateY(-10px);
}
</style>