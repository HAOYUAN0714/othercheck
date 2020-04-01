<template>
    <div class="cintainer">
        <div class="header">
            聯賽篩選
        </div>
        <div class="competiton">
            <ul>
                <li v-for="(camp,index) in campList" :key="camp.countryId" :class="{active:''}" @click="select(camp.countryId,index)">
                    {{camp.country}}
                    {{camp.count}}
                    <!-- <i class="fa fa-caret-right" aria-hidden="true"></i> -->
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

export default {
  name: 'filterCompetition',
  components: {
  },
  data() {
    return {
        campList: [
            {
                country: 'Australia',
                countryId: 0,
                count: 10
            },
            {
                country: 'Bahrain',
                countryId: 1,
                count: 1
            },
            {
                country: 'Brazil',
                countryId: 2,
                count: 3
            },
            {
                country: 'Canada',
                countryId: 3,
                count: 8
            },
            {
                country: 'China',
                countryId: 4,
                count: 7
            },
            {
                country: 'Egypt',
                countryId: 5,
                count: 11
            },
            {
                country: 'France',
                countryId: 6,
                count: 13
            },
            {
                country: 'Germany',
                countryId: 7,
                count: 17
            },
            {
                country: 'Greece',
                countryId: 8,
                count: 19
            },
            {
                country: 'Iceland',
                countryId: 9,
                count: 0
            },
            {
                country: 'India',
                countryId: 10,
                count: 0
            },
            {
                country: 'Italy',
                countryId: 11,
                count: 1
            },
            {
                country: 'Japan',
                countryId: 12,
                count: 1
            },
            {
                country: 'Korea',
                countryId: 13,
                count: 1
            },
        ]
    }
  },
  methods: {
      select(countryId, index) {
          console.log(countryId, 'countryId', index, 'index')
      }
  },
 
}
</script>

<style lang="scss" scoped>
.cintainer {
    .header {
        background-color: #000;
        // height: 20px;
        color: #fff;
        text-align: center;
        padding: 10px 0px;
    }
    .competiton {
        // border: 1px solid;
        width: 40vw;
        // height: 100vh;
        ul {
            list-style: none;
            margin: 0;
            padding: 0;
            height: 92vh;
            overflow-y: scroll;
            li {
                // border: 1px solid red;
                text-align: center;
                padding: 10px;
                background-color: #f5f7fa;
                border-bottom: 1px solid gray;
            }
            .active {
                background-color: firebrick;
            }
        }
    }
}
</style>
