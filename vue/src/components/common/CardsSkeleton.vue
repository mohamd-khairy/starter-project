<template>
  <v-row class="flex-grow-0 mb-1" dense>
      <v-col v-for="card in cardNumber" :key="card" cols="12" :lg="cardSizes.lg" :md="cardSizes.md">
        <v-card>
          <v-skeleton-loader
          :min-height="115"

          type="card-heading, list-item"
        ></v-skeleton-loader>
        </v-card>
      </v-col>

    </v-row>
</template>

<script>
export default {
  props : {
    cardPerRow: {
      type: Number,
      default: 0
    },
    cardNumber : {
      type: Number,
      default: 0
    }
  },
  data(){
    return{

    }
  },
  computed : {
    cardSizes(){
      if(this.cardPerRow % 2 === 0){
        let lgSize = 12 / this.cardPerRow
        return {
          lg : lgSize,
          md: lgSize / 2
        }
      }else{
        let lgSize = 12 / (this.cardPerRow - 1)
        return {
          lg : lgSize,
          md: lgSize / 2
        }
      }

    }
  }
}
</script>

<style>

</style>
