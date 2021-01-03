<template>
  <a :href="url">connect</a>
</template>

<script lang="ts">

import {Vue, Component} from 'vue-property-decorator'

@Component({
  components: {  },
})
export default class App extends Vue {
  connect =  Routing.generate( 'connect_google_start' )

  get url () {
    return this.connect
  }
}
</script>
