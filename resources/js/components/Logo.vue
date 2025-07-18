<template>
  <div class="logo-container" :class="{ 'invert-colors': inverted }">
    <!-- Version complète du logo -->
    <div v-if="variant === 'full'" class="logo-full flex flex-col items-center">
      <!-- Monogramme DN -->
      <div class="monogram flex items-center">
        <span class="letter-d">D</span>
        <span class="letter-n">N</span>
        <span class="red-square"></span>
      </div>
      
      <!-- Texte DIGINOVA -->
      <div class="text-container relative mt-2">
        <span class="company-name">DIGINOVA</span>
        <div class="red-underline"></div>
      </div>
    </div>
    
    <!-- Version simplifiée (monogramme seul) -->
    <div v-else class="logo-simple flex items-center">
      <span class="letter-d">D</span>
      <span class="letter-n">N</span>
      <span class="red-square"></span>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  variant?: 'full' | 'simple'; // Version complète ou simplifiée
  size?: 'sm' | 'md' | 'lg'; // Tailles prédéfinies
  inverted?: boolean; // Pour fonds sombres
}

withDefaults(defineProps<Props>(), {
  variant: 'full',
  size: 'md',
  inverted: false
});
</script>

<style scoped>
.logo-container {
  --diginova-blue: #0C1122;
  --diginova-red: #E12428;
  --white: #FFFFFF;
  
  font-family: 'Poppins', 'Montserrat', sans-serif;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

/* Tailles prédéfinies */
.logo-container {
  &.size-sm {
    font-size: 0.75rem;
    .red-square { width: 0.75em; height: 0.75em; }
  }
  &.size-md {
    font-size: 1rem;
    .red-square { width: 1em; height: 1em; }
  }
  &.size-lg {
    font-size: 1.5rem;
    .red-square { width: 1em; height: 1em; }
  }
}

/* Monogramme DN */
.monogram {
  position: relative;
  line-height: 1;
}

.letter-d {
  position: relative;
  display: inline-block;
  color: var(--diginova-blue);
}

/* Ouverture latérale du D comme dans la charte */
.letter-d::after {
  content: '';
  position: absolute;
  right: -0.1em;
  top: 0;
  bottom: 0;
  width: 0.15em;
  background-color: currentColor;
}

.letter-n {
  color: var(--diginova-blue);
  margin-left: -0.15em; /* Chevauchement léger */
}

/* Carré rouge */
.red-square {
  display: inline-block;
  background-color: var(--diginova-red);
  margin-left: 0.2em;
  vertical-align: middle;
}

/* Texte DIGINOVA */
.company-name {
  color: var(--diginova-blue);
  font-weight: bold;
  position: relative;
  display: inline-block;
}

/* Soulignement rouge */
.red-underline {
  height: 0.15em;
  width: 100%;
  background-color: var(--diginova-red);
  margin-top: 0.2em;
}

/* Version pour fonds sombres */
.invert-colors {
  .letter-d,
  .letter-n,
  .company-name {
    color: var(--white);
  }
  
  .red-square {
    background-color: var(--white);
  }
  
  .red-underline {
    background-color: var(--white);
  }
}
</style>
