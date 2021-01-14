<script>
  import InfoCard from 'tlc-front-components/src/InfoCard'

  export default {
    name: 'InfoCard',
    extends: InfoCard,
    data: () => ({
      entryPointApi: process.env.VUE_APP_ENTRY_POINT_API,
    }),
  }
</script>
