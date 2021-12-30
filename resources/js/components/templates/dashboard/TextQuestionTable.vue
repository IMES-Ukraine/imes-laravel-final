<template>
  <div class="study__table">
    <div class="study__table-block study__table-block--head">
      <div class="study__table-item">
        <p class="study__table-title">№ п</p>
      </div>
      <div class="study__table-item">
        <p class="study__table-title">ID</p>
      </div>
      <div class="study__table-item">
        <p class="study__table-title">ФИО</p>
      </div>
      <div class="study__table-item">
        <p class="study__table-title">Ответ</p>
      </div>
      <div class="study__table-item">
        <div class="study__table-controls--title">
          <p>Принять</p>
          <p>Отклонить</p>
        </div>
      </div>
    </div>
    <template v-if="moderations">
      <div class="study__table-block" v-for="(moderation, key) in moderations.data">
        <div class="study__table-item">
          <p class="study__table-number">{{ key + 1 }}</p>
        </div>
        <div class="study__table-item">
          <p class="study__table-id">
            {{ (moderation.user) ? moderation.user.id : 0 }}</p>
        </div>
        <div class="study__table-item">
          <p class="study__table-name">
            {{
            (moderation.user) ? moderation.user.name : 'Уже нет такого пользователя'
            }}</p>
        </div>
        <div class="study__table-item">
          <p class="study__table-description">{{ moderation.answer }}</p>
        </div>
        <div class="study__table-item">
          <div class="study__table-controls">
            <button
                :class="(moderation.status=='accept')?class_plus + ' active':class_plus"
                type="button"
                :disabled="(moderation.status=='cancel')?true:false"
                @click="accept"></button>
            <button
                :class="(moderation.status=='cancel')?class_minus + ' active':class_minus"
                type="button"
                :disabled="(moderation.status=='accept')?true:false"
                @click="decline"></button>
          </div>
        </div>
      </div>
      <div class="articles_pagination center">
        <pagination :data="moderations"
                    @pagination-change-page="getResults"></pagination>
      </div>
    </template>
  </div>
</template>
<script>
export default {
  name: 'text-question-table',
  props: {
    accept: {},
    class_minus: {},
    class_plus: {},
    decline: {},
    getResults: {},
    moderations: {}
  }
}
</script>