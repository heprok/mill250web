<script>
  import CRUDTable from 'tlc-front-components/src/CRUDTable'

  export default {
    name: 'CRUDTable',
    extends: CRUDTable,
    data: () => ({
      entryPointApi: process.env.VUE_APP_ENTRY_POINT_API || '',
    }),
  }
</script>
