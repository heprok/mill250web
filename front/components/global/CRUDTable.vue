<script>
  import CRUDTable from 'tlc-front-components/src/CRUDTable'

  export default {
    name: 'CRUDTable',
    extends: CRUDTable,
    data(){
      return {
        entryPointApi: this.$store.state.apiEntryPoint,
      } 
    },
  }
</script>
