<date-range-picker
  ref="picker"
  :opens="opens"
  :locale-data="{ firstDay: 1, format: 'dd-mm-yyyy HH:mm:ss' }"
  :minDate="minDate" :maxDate="maxDate"
  :singleDatePicker="singleDatePicker"
  :timePicker="timePicker"
  :timePicker24Hour="timePicker24Hour"
  :showWeekNumbers="showWeekNumbers"
  :showDropdowns="showDropdowns"
  :autoApply="autoApply"
  v-model="dateRange"
  @update="updateValues"
  @toggle="logEvent('event: open', $event)"
  @start-selection="logEvent('event: startSelection', $event)"
  @finish-selection="logEvent('event: finishSelection', $event)"
  :linkedCalendars="linkedCalendars"
  :dateFormat="dateFormat"
>
<template v-slot:input="picker" style="min-width: 350px;">
  {{ picker.startDate | date }} - {{ picker.endDate | date }}
</template>
</date-range-picker>
