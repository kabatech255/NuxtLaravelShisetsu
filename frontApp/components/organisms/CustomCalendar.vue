<template>
  <v-calendar
    :attributes="schedules.attributes"
    :masks="schedules.masks"
    :rows="1"
    class="CustomCalendar"
    is-expanded
  >
    <template v-slot:day-content="{ day, attributes }">
      <div class="CustomCalendar_attr" :class="{ '--today': day.isToday }">
        <div class="CustomCalender_film" @click="onDay(day)"></div>
        <span class="CustomCalendar_attrLabel">
          <small class="CustomCalendar_attrDay">{{ day.day }}</small>
        </span>
        <div class="CustomCalendar_attrBody">
          <div
            v-for="(attr, index) in attributes"
            :key="index"
            class="CustomCalendar_attrBox"
          >
            <span
              class="CustomCalendar_attrValue"
              :style="attr.customData.style"
              @click="toggleDetail(`${day.id}-${attr.key}`)"
              >{{ attr.customData.title }}</span
            >
            <div
              :ref="`overlay_${day.id}-${attr.key}`"
              class="CustomCalendar_overlay"
              @click="toggleDetail(`${day.id}-${attr.key}`)"
            ></div>
            <div
              :ref="`detail_${day.id}-${attr.key}`"
              class="CustomCalendar_detail"
            >
              <Icon
                v-if="attr.customData.is_private"
                name="lock"
                class="CustomCalendar_secretMark"
              />
              <div class="CustomCalendar_operator">
                <button
                  v-if="attr.customData.can_edit"
                  class="CustomCalendar_operatorItem"
                >
                  <Icon
                    class="CustomCalendar_operatorIcon"
                    name="create"
                    @click="$emit('onEdit', attr)"
                  />
                </button>
                <button
                  v-if="attr.customData.can_edit"
                  class="CustomCalendar_operatorItem u-ml10"
                >
                  <Icon
                    class="CustomCalendar_operatorIcon"
                    name="delete"
                    @click="$emit('onDelete', attr)"
                  />
                </button>
                <button class="CustomCalendar_operatorItem u-ml20">
                  <Icon
                    class="CustomCalendar_operatorIcon"
                    name="clear"
                    @click="toggleDetail(`${day.id}-${attr.key}`)"
                  />
                </button>
              </div>
              <ul>
                <li class="CustomCalendar_detailRow --vertical u-mt10">
                  <div class="CustomCalendar_detailCol --titleLabel">
                    <span
                      :style="attr.customData.style"
                      class="CustomCalender_titleLabel"
                    ></span>
                  </div>
                  <div class="CustomCalendar_detailCol --title">
                    <p class="CustomCalendar_detailTitle">
                      <span>{{ attr.customData.body }}</span>
                    </p>
                  </div>
                </li>
                <li class="CustomCalendar_detailRow u-mt15">
                  <div class="CustomCalendar_detailCol --label">開始日:</div>
                  <div class="CustomCalendar_detailCol">
                    {{ attr.customData.startDisp }}
                  </div>
                </li>
                <li class="CustomCalendar_detailRow u-mt15">
                  <div class="CustomCalendar_detailCol --label">終了日:</div>
                  <div class="CustomCalendar_detailCol">
                    {{ attr.customData.endDisp }}
                  </div>
                </li>
                <li class="CustomCalendar_detailRow u-mt15">
                  <div class="CustomCalendar_detailCol --label">共有者:</div>
                  <div class="CustomCalendar_detailCol">
                    {{ sharedNames(attr) }}
                  </div>
                </li>
                <li class="CustomCalendar_detailRow u-mt15">
                  <div class="CustomCalendar_detailCol --label">編集権者:</div>
                  <div class="CustomCalendar_detailCol">
                    {{ editableNames(attr) }}
                  </div>
                </li>
                <li class="CustomCalendar_detailRow u-mt15">
                  <div class="CustomCalendar_detailCol --label">公開状況:</div>
                  <div class="CustomCalendar_detailCol">
                    {{ privateLabel(attr) }}
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </template>
  </v-calendar>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import { ScheduleData, ScheduleObj, SharedMember } from '~/types/Dashboard'
import Icon from '~/components/atoms/Icon.vue'
@Component({
  components: { Icon },
})
export default class CustomCalendar extends Vue {
  @Prop({ type: Object, required: true }) schedules!: ScheduleData

  toggleDetail(dayId: string): void {
    const detail: any = this.$refs[`detail_${dayId}`]
    const overlay: any = this.$refs[`overlay_${dayId}`]
    this.toggleActiveClass(detail[0])
    this.toggleActiveClass(overlay[0])
  }

  toggleActiveClass(el: any) {
    if (el.classList.contains('--active')) {
      el.classList.remove('--active')
    } else {
      el.classList.add('--active')
    }
  }

  sharedNames(attr: ScheduleObj): string {
    if (this.isToday(attr)) {
      return ''
    }
    const namesArr: string[] = attr.customData.shared_members.map(
      (member: SharedMember): string => member.name
    )

    let joined: string = namesArr.splice(0, 10).join(', ')
    if (namesArr.length > 0) {
      joined += `\n\n他${namesArr.length}名`
    }
    return joined
  }

  editableNames(attr: ScheduleObj): string {
    if (this.isToday(attr)) {
      return ''
    }
    return attr.customData.editable_members
      .map((member: SharedMember): string => member.name)
      .join(',　')
  }

  privateLabel(attr: ScheduleObj): string {
    return attr.customData.is_private ? '非公開' : '公開中'
  }

  isToday(attr: ScheduleObj): boolean {
    return attr.key === 1
  }

  onDay(day: any): void {
    this.$emit('onDayClick', day)
  }
}
</script>
<style lang="scss" scoped>
.vc-is-expanded {
  max-width: 100%;
  height: 100%;
}
.vc-weekday {
  max-width: calc(100% / 7);
  overflow: hidden;
}
</style>
