<template>
  <v-footer height="40" app>
    <div class="text-caption text-disabled">
      Annuaire interne, version {{ version }} - {{ dateversion }}  / données SOI - Active Directory
    </div>

    <div
      class="text-caption text-disabled"
      style="position: absolute; right: 16px;"
    >
      &copy; 2000-{{ (new Date()).getFullYear() }} <span class="d-none d-sm-inline-block">Goéland</span>
    </div>
  </v-footer>
</template>

<script setup>
  import { data } from '@/stores/data.js'
  let lesData = data()
  const version = lesData.version
  const dateversion = lesData.dateversion
</script>

<style scoped lang="sass">
  .social-link :deep(.v-icon)
    color: rgba(var(--v-theme-on-background), var(--v-disabled-opacity))
    text-decoration: none
    transition: .2s ease-in-out

    &:hover
      color: rgba(25, 118, 210, 1)
</style>
