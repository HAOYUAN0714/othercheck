<template>
  <div class="datePicker">
    <input
      type="text"
      class="flatPickr"
      :class="{'selected': selectedDate}"
      :value="selectedDate"
      readonly
      ref="firstDatePicker"
      :placeholder="$t('TEXT_PLEASE_ENTER_DATE')"
    />
    <div class="expandIcon">
      <i class="el-icon-arrow-down"></i>
    </div>
  </div>
</template>
<style lang="scss">
.datePicker {
  margin: 0 2px;
  position: relative;
  & > input {
    padding: 5px;
    outline: none;
  }
  & > .expandIcon {
    position: absolute;
    right: 10px;
    top: 0;
  }
}
</style>
<script>
import Flatpickr from "flatpickr";
import $ from "jquery";

export default {
  data() {
    return {
      datePicker: {}
    };
  },

  props: {
    dateArr: {
      type: Array,
      default: () => []
    },
    selectedDate: {
      type: String,
      dafault: () => ""
    }
  },
  watch: {
    selectedDate(val, oldVal) {
      this.datePicker.setDate(val);
    },
    dateArr(val, oldVal) {
      if (val.length === 0) {
        this.datePicker.set("enable", ["1911-01-01"]);
      } else {
        this.datePicker.set("enable", val);
      }
      this.datePicker.redraw();
    }
  },
  methods: {},
  mounted() {
    const { selectedDate } = this;
    const vm = this;
    this.datePicker = new Flatpickr(this.$refs.firstDatePicker, {
      dateFormat: "Y/m/d",
      defaultDate: selectedDate,
      // 關閉遮罩
      animate: false,
      allowInput: true,
      onChange(selectedDates, dateStr, instance) {
        const onChange = () => {
          vm.$emit("selectDate", dateStr);
        };
        if (vm.routerName && vm.$route.name !== vm.routerName) {
          vm.$router.push({
            name: vm.routerName,
            params: { beforeFunc: onChange }
          });
        } else {
          onChange();
        }
      },
      onClose(selectedDates, dateStr, instance) {
        if (vm.dateArr.find(date => vm.selectedDate === date)) {
          instance.setDate(vm.selectedDate);
        }
        $(vm.$refs.firstDatePicker).blur();
      }
    });
    this.datePicker.set("enable", this.dateArr);
  },
  beforeDestroy() {
    this.datePicker.destroy();
  }
};
</script>
