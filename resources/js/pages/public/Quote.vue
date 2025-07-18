<template>
  <AppLayout>
    <section class="py-16 sm:py-24 bg-gray-50 font-diginova">
      <div class="container mx-auto px-4 sm:px-6 max-w-4xl">
        <!-- En-tête -->
        <div class="text-center mb-12">
          <h2 class="text-3xl sm:text-4xl font-bold text-diginova-blue mb-4 diginova-uppercase">
            Demande de devis
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Remplissez ce formulaire pour obtenir un devis personnalisé
          </p>
        </div>

        <!-- Stepper -->
        <div class="mb-8">
          <div class="flex justify-between relative">
            <!-- Ligne de progression -->
            <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-200 -z-10"></div>
            <div 
              class="absolute top-1/2 left-0 h-1 bg-diginova-red transition-all duration-500 -z-10"
              :style="`width: ${progress}%`"
            ></div>

            <!-- Étapes -->
            <div 
              v-for="(step, index) in steps" 
              :key="index"
              class="flex flex-col items-center"
              @click="currentStep > index ? goToStep(index) : null"
              :class="{ 'cursor-pointer': currentStep > index }"
            >
              <div 
                class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all"
                :class="{
                  'bg-diginova-red border-diginova-red text-white': currentStep >= index,
                  'border-gray-300': currentStep < index,
                  'bg-white': currentStep < index
                }"
              >
                <span v-if="currentStep > index">
                  <i class="bi bi-check-lg"></i>
                </span>
                <span v-else>
                  {{ index + 1 }}
                </span>
              </div>
              <span 
                class="mt-2 text-sm font-medium"
                :class="{
                  'text-diginova-red': currentStep >= index,
                  'text-gray-500': currentStep < index
                }"
              >
                {{ step.label }}
              </span>
            </div>
          </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-md p-6 sm:p-8 border border-diginova-red/20">
          <!-- Étape 1 : Type de service -->
          <div v-if="currentStep === 0">
            <h3 class="text-xl font-bold text-diginova-blue mb-6">Quel service vous intéresse ?</h3>
            
            <div class="grid md:grid-cols-2 gap-4">
              <div 
                v-for="service in services" 
                :key="service.id"
                @click="selectService(service)"
                class="border rounded-lg p-4 cursor-pointer transition-all hover:border-diginova-red hover:shadow-md"
                :class="{
                  'border-diginova-red bg-diginova-red/5': form.service === service.id
                }"
              >
                <div class="flex items-start">
                  <div class="p-2 rounded-full bg-diginova-red/10 text-diginova-red mr-4">
                    <i class="bi" :class="service.icon"></i>
                  </div>
                  <div>
                    <h4 class="font-bold text-diginova-blue">{{ service.label }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ service.description }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Étape 2 : Détails spécifiques -->
          <div v-if="currentStep === 1">
            <!-- Développement Web/Mobile -->
            <div v-if="form.service === 1 || form.service === 2">
              <h3 class="text-xl font-bold text-diginova-blue mb-6">Spécifications du projet</h3>
              
              <div class="space-y-6">
                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Type de projet</label>
                  <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <button
                      v-for="type in projectTypes"
                      :key="type.id"
                      @click="form.projectType = type.id"
                      type="button"
                      class="px-4 py-2 border rounded-md text-center transition-all"
                      :class="{
                        'border-diginova-red bg-diginova-red/10': form.projectType === type.id,
                        'border-gray-300': form.projectType !== type.id
                      }"
                    >
                      {{ type.label }}
                    </button>
                  </div>
                </div>

                <div>
                  <label for="project-name" class="block text-sm font-medium text-diginova-blue mb-1">Nom du projet</label>
                  <input 
                    id="project-name" 
                    v-model="form.projectName"
                    type="text" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                    placeholder="Ex: Application mobile de gestion de stock"
                    required
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Disposez-vous d'un cahier des charges ?</label>
                  <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                      <input 
                        type="radio" 
                        v-model="form.hasSpecs" 
                        :value="true" 
                        class="text-diginova-red focus:ring-diginova-red"
                      >
                      <span class="ml-2">Oui</span>
                    </label>
                    <label class="inline-flex items-center">
                      <input 
                        type="radio" 
                        v-model="form.hasSpecs" 
                        :value="false" 
                        class="text-diginova-red focus:ring-diginova-red"
                      >
                      <span class="ml-2">Non</span>
                    </label>
                  </div>
                </div>

                <div v-if="form.hasSpecs">
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Joindre votre cahier des charges</label>
                  <div class="mt-1 flex items-center">
                    <input
                      type="file"
                      ref="specsFile"
                      @change="handleFileUpload"
                      class="hidden"
                      accept=".pdf,.doc,.docx,.xls,.xlsx"
                    >
                    <button
                      type="button"
                      @click="$refs.specsFile.click()"
                      class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-diginova-red"
                    >
                      <i class="bi bi-paperclip mr-2"></i>
                      {{ form.specsFile ? form.specsFile.name : 'Sélectionner un fichier' }}
                    </button>
                    <span v-if="form.specsFile" class="ml-3 text-sm text-gray-500">
                      <i class="bi bi-check-circle text-green-500 mr-1"></i>
                      Fichier sélectionné
                    </span>
                  </div>
                  <p class="mt-1 text-sm text-gray-500">
                    Formats acceptés : PDF, Word, Excel (max 10MB)
                  </p>
                </div>

                <div v-if="!form.hasSpecs">
                  <label for="project-desc" class="block text-sm font-medium text-diginova-blue mb-1">Description détaillée</label>
                  <textarea 
                    id="project-desc" 
                    v-model="form.projectDescription"
                    rows="6"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                    placeholder="Décrivez en détail votre projet : objectifs, fonctionnalités souhaitées, publics cibles, contraintes techniques..."
                    required
                  ></textarea>
                  <p class="mt-1 text-sm text-gray-500">
                    Plus votre description sera précise, plus notre devis sera accurate
                  </p>
                </div>

                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Délai souhaité</label>
                  <div class="grid grid-cols-3 gap-3">
                    <button
                      v-for="timeline in timelines"
                      :key="timeline.id"
                      @click="form.timeline = timeline.id"
                      type="button"
                      class="px-4 py-2 border rounded-md text-center transition-all"
                      :class="{
                        'border-diginova-red bg-diginova-red/10': form.timeline === timeline.id,
                        'border-gray-300': form.timeline !== timeline.id
                      }"
                    >
                      {{ timeline.label }}
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Matériel Informatique -->
            <div v-else-if="form.service === 3">
              <h3 class="text-xl font-bold text-diginova-blue mb-6">Configuration matérielle</h3>
              
              <div class="space-y-6">
                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Type de matériel</label>
                  <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <button
                      v-for="hardware in hardwareTypes"
                      :key="hardware.id"
                      @click="form.hardwareType = hardware.id"
                      type="button"
                      class="px-4 py-2 border rounded-md text-center transition-all"
                      :class="{
                        'border-diginova-red bg-diginova-red/10': form.hardwareType === hardware.id,
                        'border-gray-300': form.hardwareType !== hardware.id
                      }"
                    >
                      {{ hardware.label }}
                    </button>
                  </div>
                </div>

                <div v-if="form.hardwareType">
                  <label for="hardware-specs" class="block text-sm font-medium text-diginova-blue mb-1">Spécifications techniques</label>
                  <textarea 
                    id="hardware-specs" 
                    v-model="form.hardwareSpecs"
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                    placeholder="Décrivez vos besoins en termes de processeur, RAM, stockage, GPU, etc."
                    required
                  ></textarea>
                </div>

                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Quantité</label>
                  <input 
                    type="number" 
                    v-model="form.quantity"
                    min="1"
                    class="w-24 px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                    required
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Besoin d'installation ?</label>
                  <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                      <input 
                        type="radio" 
                        v-model="form.needInstallation" 
                        :value="true" 
                        class="text-diginova-red focus:ring-diginova-red"
                        required
                      >
                      <span class="ml-2">Oui</span>
                    </label>
                    <label class="inline-flex items-center">
                      <input 
                        type="radio" 
                        v-model="form.needInstallation" 
                        :value="false" 
                        class="text-diginova-red focus:ring-diginova-red"
                        required
                      >
                      <span class="ml-2">Non</span>
                    </label>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Délai souhaité</label>
                  <div class="grid grid-cols-3 gap-3">
                    <button
                      v-for="timeline in timelines"
                      :key="timeline.id"
                      @click="form.timeline = timeline.id"
                      type="button"
                      class="px-4 py-2 border rounded-md text-center transition-all"
                      :class="{
                        'border-diginova-red bg-diginova-red/10': form.timeline === timeline.id,
                        'border-gray-300': form.timeline !== timeline.id
                      }"
                    >
                      {{ timeline.label }}
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Maintenance & Support -->
            <div v-else-if="form.service === 4">
              <h3 class="text-xl font-bold text-diginova-blue mb-6">Besoin de maintenance</h3>
              
              <div class="space-y-6">
                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Type de maintenance</label>
                  <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <button
                      v-for="maintenance in maintenanceTypes"
                      :key="maintenance.id"
                      @click="form.maintenanceType = maintenance.id"
                      type="button"
                      class="px-4 py-2 border rounded-md text-center transition-all"
                      :class="{
                        'border-diginova-red bg-diginova-red/10': form.maintenanceType === maintenance.id,
                        'border-gray-300': form.maintenanceType !== maintenance.id
                      }"
                    >
                      {{ maintenance.label }}
                    </button>
                  </div>
                </div>

                <div>
                  <label for="equipment-desc" class="block text-sm font-medium text-diginova-blue mb-1">Équipements concernés</label>
                  <textarea 
                    id="equipment-desc" 
                    v-model="form.equipmentDescription"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                    placeholder="Listez les équipements/systèmes à maintenir avec leurs caractéristiques"
                    required
                  ></textarea>
                </div>

                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Fréquence souhaitée</label>
                  <select 
                    v-model="form.frequency"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                    required
                  >
                    <option value="">Sélectionnez une fréquence</option>
                    <option value="ponctuel">Ponctuelle (une fois)</option>
                    <option value="hebdomadaire">Hebdomadaire</option>
                    <option value="mensuel">Mensuelle</option>
                    <option value="trimestriel">Trimestrielle</option>
                    <option value="annuel">Annuelle</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Contrat de maintenance</label>
                  <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                      <input 
                        type="radio" 
                        v-model="form.contractType" 
                        value="standard" 
                        class="text-diginova-red focus:ring-diginova-red"
                        required
                      >
                      <span class="ml-2">Standard (8h-18h)</span>
                    </label>
                    <label class="inline-flex items-center">
                      <input 
                        type="radio" 
                        v-model="form.contractType" 
                        value="premium" 
                        class="text-diginova-red focus:ring-diginova-red"
                        required
                      >
                      <span class="ml-2">Premium 24/7</span>
                    </label>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-diginova-blue mb-1">Délai souhaité</label>
                  <div class="grid grid-cols-3 gap-3">
                    <button
                      v-for="timeline in timelines"
                      :key="timeline.id"
                      @click="form.timeline = timeline.id"
                      type="button"
                      class="px-4 py-2 border rounded-md text-center transition-all"
                      :class="{
                        'border-diginova-red bg-diginova-red/10': form.timeline === timeline.id,
                        'border-gray-300': form.timeline !== timeline.id
                      }"
                    >
                      {{ timeline.label }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Étape 3 : Informations client -->
          <div v-if="currentStep === 2">
            <h3 class="text-xl font-bold text-diginova-blue mb-6">Vos coordonnées</h3>
            
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label for="firstname" class="block text-sm font-medium text-diginova-blue mb-1">Prénom</label>
                <input 
                  id="firstname" 
                  v-model="form.firstName"
                  type="text" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                  required
                >
              </div>

              <div>
                <label for="lastname" class="block text-sm font-medium text-diginova-blue mb-1">Nom</label>
                <input 
                  id="lastname" 
                  v-model="form.lastName"
                  type="text" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                  required
                >
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-diginova-blue mb-1">Email</label>
                <input 
                  id="email" 
                  v-model="form.email"
                  type="email" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                  required
                >
              </div>

              <div>
                <label for="phone" class="block text-sm font-medium text-diginova-blue mb-1">Téléphone</label>
                <input 
                  id="phone" 
                  v-model="form.phone"
                  type="tel" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                >
              </div>

              <div class="md:col-span-2">
                <label for="company" class="block text-sm font-medium text-diginova-blue mb-1">Entreprise (optionnel)</label>
                <input 
                  id="company" 
                  v-model="form.company"
                  type="text" 
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-diginova-red focus:border-diginova-red"
                >
              </div>
            </div>
          </div>

          <!-- Étape 4 : Récapitulatif -->
          <div v-if="currentStep === 3">
            <h3 class="text-xl font-bold text-diginova-blue mb-6">Récapitulatif</h3>
            
            <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-diginova-red/10">
              <h4 class="font-bold text-lg text-diginova-blue mb-4">Votre demande</h4>
              
              <div class="space-y-4">
                <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                  <span class="text-gray-600">Service :</span>
                  <span class="font-medium text-diginova-blue">
                    {{ selectedService?.label || 'Non spécifié' }}
                  </span>
                </div>

                <!-- Affichage conditionnel selon le service -->
                <template v-if="form.service === 1 || form.service === 2">
                  <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                    <span class="text-gray-600">Type de projet :</span>
                    <span class="font-medium text-diginova-blue">
                      {{ selectedProjectType?.label || 'Non spécifié' }}
                    </span>
                  </div>

                  <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                    <span class="text-gray-600">Nom du projet :</span>
                    <span class="font-medium text-diginova-blue">
                      {{ form.projectName || 'Non spécifié' }}
                    </span>
                  </div>

                  <div class="border-b pb-2 border-diginova-red/10">
                    <p class="text-gray-600 mb-1">Description :</p>
                    <p class="font-medium text-diginova-blue">
                      {{ form.projectDescription || (form.hasSpecs ? 'Cahier des charges joint' : 'Non spécifié') }}
                    </p>
                  </div>
                </template>

                <template v-else-if="form.service === 3">
                  <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                    <span class="text-gray-600">Type de matériel :</span>
                    <span class="font-medium text-diginova-blue">
                      {{ selectedHardwareType?.label || 'Non spécifié' }}
                    </span>
                  </div>

                  <div class="border-b pb-2 border-diginova-red/10">
                    <p class="text-gray-600 mb-1">Spécifications :</p>
                    <p class="font-medium text-diginova-blue">
                      {{ form.hardwareSpecs || 'Non spécifié' }}
                    </p>
                  </div>

                  <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                    <span class="text-gray-600">Quantité :</span>
                    <span class="font-medium text-diginova-blue">
                      {{ form.quantity }}
                    </span>
                  </div>

                  <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                    <span class="text-gray-600">Installation :</span>
                    <span class="font-medium text-diginova-blue">
                      {{ form.needInstallation ? 'Oui' : 'Non' }}
                    </span>
                  </div>
                </template>

                <template v-else-if="form.service === 4">
                  <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                    <span class="text-gray-600">Type de maintenance :</span>
                    <span class="font-medium text-diginova-blue">
                      {{ selectedMaintenanceType?.label || 'Non spécifié' }}
                    </span>
                  </div>

                  <div class="border-b pb-2 border-diginova-red/10">
                    <p class="text-gray-600 mb-1">Équipements :</p>
                    <p class="font-medium text-diginova-blue">
                      {{ form.equipmentDescription || 'Non spécifié' }}
                    </p>
                  </div>

                  <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                    <span class="text-gray-600">Fréquence :</span>
                    <span class="font-medium text-diginova-blue">
                      {{ formatFrequency(form.frequency) }}
                    </span>
                  </div>

                  <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                    <span class="text-gray-600">Contrat :</span>
                    <span class="font-medium text-diginova-blue">
                      {{ form.contractType === 'premium' ? 'Premium 24/7' : 'Standard (8h-18h)' }}
                    </span>
                  </div>
                </template>

                <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                  <span class="text-gray-600">Délai souhaité :</span>
                  <span class="font-medium text-diginova-blue">
                    {{ selectedTimeline?.label || 'Non spécifié' }}
                  </span>
                </div>
              </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 border border-diginova-red/10">
              <h4 class="font-bold text-lg text-diginova-blue mb-4">Vos informations</h4>
              
              <div class="space-y-4">
                <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                  <span class="text-gray-600">Nom complet :</span>
                  <span class="font-medium text-diginova-blue">
                    {{ form.firstName }} {{ form.lastName }}
                  </span>
                </div>

                <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                  <span class="text-gray-600">Email :</span>
                  <span class="font-medium text-diginova-blue">
                    {{ form.email }}
                  </span>
                </div>

                <div class="flex justify-between border-b pb-2 border-diginova-red/10">
                  <span class="text-gray-600">Téléphone :</span>
                  <span class="font-medium text-diginova-blue">
                    {{ form.phone || 'Non spécifié' }}
                  </span>
                </div>

                <div class="flex justify-between">
                  <span class="text-gray-600">Entreprise :</span>
                  <span class="font-medium text-diginova-blue">
                    {{ form.company || 'Non spécifié' }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation -->
          <div class="flex justify-between mt-8">
            <button
              v-if="currentStep > 0"
              @click="prevStep"
              type="button"
              class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
            >
              <i class="bi bi-arrow-left mr-2"></i> Retour
            </button>
            <div v-else></div>

            <button
              v-if="currentStep < steps.length - 1"
              @click="nextStep"
              type="button"
              class="px-6 py-2 bg-diginova-red text-white rounded-md hover:bg-diginova-red/90 transition-colors diginova-uppercase"
              :disabled="!canProceed"
              :class="{ 'opacity-50 cursor-not-allowed': !canProceed }"
            >
              Suivant <i class="bi bi-arrow-right ml-2"></i>
            </button>

            <button
              v-if="currentStep === steps.length - 1"
              @click="submitQuote"
              type="button"
              class="px-6 py-2 bg-diginova-blue text-white rounded-md hover:bg-diginova-blue/90 transition-colors diginova-uppercase"
              :disabled="submitting"
              :class="{ 'opacity-50 cursor-not-allowed': submitting }"
            >
              <span v-if="submitting">
                <i class="bi bi-arrow-repeat animate-spin mr-2"></i> Envoi en cours...
              </span>
              <span v-else>
                <i class="bi bi-send-check mr-2"></i> Envoyer la demande
              </span>
            </button>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppLayout from '@/layouts/AppLayoutPublic.vue'

// Configuration des étapes
const steps = [
  { label: 'Service', key: 'service' },
  { label: 'Détails', key: 'details' },
  { label: 'Coordonnées', key: 'contact' },
  { label: 'Validation', key: 'validation' }
]

// Données du formulaire
const form = ref({
  service: null,
  // Développement
  projectType: null,
  hasSpecs: null,
  specsFile: null,
  projectName: '',
  projectDescription: '',
  // Matériel
  hardwareType: null,
  hardwareSpecs: '',
  quantity: 1,
  needInstallation: false,
  // Maintenance
  maintenanceType: null,
  equipmentDescription: '',
  frequency: '',
  contractType: 'standard',
  // Commun
  timeline: null,
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  company: ''
})

// Options des sélecteurs
const services = ref([
  { id: 1, label: 'Développement Web', icon: 'bi-code-slash', description: 'Site vitrine, e-commerce, application web sur mesure' },
  { id: 2, label: 'Application Mobile', icon: 'bi-phone', description: 'Applications iOS et Android natives ou cross-platform' },
  { id: 3, label: 'Matériel Informatique', icon: 'bi-pc-display', description: 'PC, accessoires et solutions matérielles' },
  { id: 4, label: 'Maintenance & Support', icon: 'bi-tools', description: 'Assistance technique et maintenance préventive' }
])

const projectTypes = ref([
  { id: 'website', label: 'Site Web' },
  { id: 'ecommerce', label: 'E-commerce' },
  { id: 'webapp', label: 'Application Web' },
  { id: 'cms', label: 'CMS/Admin' },
  { id: 'api', label: 'API/Backend' },
  { id: 'other', label: 'Autre' }
])

const hardwareTypes = ref([
  { id: 'workstation', label: 'Poste de travail' },
  { id: 'laptop', label: 'Portable' },
  { id: 'server', label: 'Serveur' },
  { id: 'network', label: 'Réseau' },
  { id: 'peripheral', label: 'Périphérique' },
  { id: 'other', label: 'Autre' }
])

const maintenanceTypes = ref([
  { id: 'preventive', label: 'Préventive' },
  { id: 'corrective', label: 'Corrective' },
  { id: 'evolution', label: 'Évolutive' },
  { id: 'urgent', label: 'Urgente' },
  { id: 'full', label: 'Complète' }
])

const timelines = ref([
  { id: 1, label: 'Moins de 1 mois' },
  { id: 2, label: '1 à 3 mois' },
  { id: 3, label: 'Plus de 3 mois' }
])

// Références
const specsFile = ref(null)

// Calculs
const progress = computed(() => {
  return (currentStep.value / (steps.length - 1)) * 100
})

const selectedService = computed(() => {
  return services.value.find(s => s.id === form.value.service)
})

const selectedProjectType = computed(() => {
  return projectTypes.value.find(t => t.id === form.value.projectType)
})

const selectedHardwareType = computed(() => {
  return hardwareTypes.value.find(t => t.id === form.value.hardwareType)
})

const selectedMaintenanceType = computed(() => {
  return maintenanceTypes.value.find(t => t.id === form.value.maintenanceType)
})

const selectedTimeline = computed(() => {
  return timelines.value.find(t => t.id === form.value.timeline)
})

const canProceed = computed(() => {
  switch(currentStep.value) {
    case 0: 
      return form.value.service !== null
    case 1:
      if (form.value.service === 1 || form.value.service === 2) {
        return form.value.projectType && form.value.projectName && 
               (form.value.hasSpecs ? true : form.value.projectDescription) &&
               form.value.timeline
      } else if (form.value.service === 3) {
        return form.value.hardwareType && form.value.hardwareSpecs && 
               form.value.quantity && form.value.timeline
      } else if (form.value.service === 4) {
        return form.value.maintenanceType && form.value.equipmentDescription && 
               form.value.frequency && form.value.contractType && form.value.timeline
      }
      return false
    case 2:
      return form.value.firstName && form.value.lastName && form.value.email
    default:
      return true
  }
})

// État du composant
const currentStep = ref(0)
const submitting = ref(false)

// Méthodes
const selectService = (service) => {
  form.value.service = service.id
  // Réinitialiser les champs spécifiques au service
  if (service.id === 1 || service.id === 2) {
    form.value.projectType = null
    form.value.hasSpecs = null
    form.value.specsFile = null
    form.value.projectName = ''
    form.value.projectDescription = ''
  } else if (service.id === 3) {
    form.value.hardwareType = null
    form.value.hardwareSpecs = ''
    form.value.quantity = 1
    form.value.needInstallation = false
  } else if (service.id === 4) {
    form.value.maintenanceType = null
    form.value.equipmentDescription = ''
    form.value.frequency = ''
    form.value.contractType = 'standard'
  }
}

const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Vérification de la taille (10MB max)
    if (file.size > 10 * 1024 * 1024) {
      alert('Le fichier est trop volumineux (max 10MB)')
      return
    }
    form.value.specsFile = file
  }
}

const formatFrequency = (frequency) => {
  switch(frequency) {
    case 'ponctuel': return 'Ponctuelle (une fois)'
    case 'hebdomadaire': return 'Hebdomadaire'
    case 'mensuel': return 'Mensuelle'
    case 'trimestriel': return 'Trimestrielle'
    case 'annuel': return 'Annuelle'
    default: return 'Non spécifié'
  }
}

const nextStep = () => {
  if (canProceed.value) {
    currentStep.value++
  }
}

const prevStep = () => {
  currentStep.value--
}

const goToStep = (index) => {
  if (index < currentStep.value) {
    currentStep.value = index
  }
}

const submitQuote = async () => {
  submitting.value = true
  
  try {
    // Création du FormData pour gérer les fichiers
    const formData = new FormData()
    
    // Ajout des champs du formulaire
    for (const key in form.value) {
      if (key === 'specsFile' && form.value[key]) {
        formData.append('specs_file', form.value[key])
      } else {
        formData.append(key, form.value[key])
      }
    }

    // Ici vous feriez l'appel API pour soumettre le devis
    // await axios.post('/api/quotes', formData, {
    //   headers: {
    //     'Content-Type': 'multipart/form-data'
    //   }
    // })
    
    // Simulation d'un délai d'envoi
        // Simulation d'un délai d'envoi
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    // Réinitialisation du formulaire après soumission réussie
    resetForm()
    
    // Redirection vers une page de confirmation
    // router.push('/quote/confirmation')
    
    // Pour l'exemple, on affiche juste un message
    alert('Votre demande de devis a bien été envoyée ! Nous vous contacterons sous 48h.')
    
  } catch (error) {
    console.error('Erreur lors de la soumission du devis:', error)
    alert('Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer.')
  } finally {
    submitting.value = false
  }
}

const resetForm = () => {
  // Réinitialisation complète du formulaire
  form.value = {
    service: null,
    projectType: null,
    hasSpecs: null,
    specsFile: null,
    projectName: '',
    projectDescription: '',
    hardwareType: null,
    hardwareSpecs: '',
    quantity: 1,
    needInstallation: false,
    maintenanceType: null,
    equipmentDescription: '',
    frequency: '',
    contractType: 'standard',
    timeline: null,
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    company: ''
  }
  
  // Réinitialisation du stepper
  currentStep.value = 0
  
  // Réinitialisation de l'input fichier
  if (specsFile.value) {
    specsFile.value.value = ''
  }
}
</script>

<style scoped>
/* Animation pour les transitions entre étapes */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Style personnalisé pour les boutons radio */
input[type="radio"] {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  width: 16px;
  height: 16px;
  border: 2px solid #ccc;
  border-radius: 50%;
  outline: none;
  transition: all 0.2s ease;
}

input[type="radio"]:checked {
  border-color: #E31937;
  background-color: #E31937;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='8' cy='8' r='4'/%3E%3C/svg%3E");
}

/* Style pour le hover sur les cartes de service */
.service-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Style pour le bouton de fichier */
.file-button:hover {
  background-color: #f9fafb;
}

/* Style pour les transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>