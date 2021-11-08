<template>
  <div>
    <div class="articles_create-block" v-for="(variant, index) in variants" :key="variant.itemId"
         :id="'block-'+variant.itemId">
      <div class="articles_create-line"></div>
      <div class="articles_create__item">
        <div class="articles_create__item-title has_radio">
          <input type="radio"
                 :name="'answer_'+variant.itemId"
                 :value="1"
                 v-model="localType"/>
          <i></i>
          <p>Готовый <br>ответ</p>
        </div>
        <div class="articles_create__item-content">
          <div class="articles_create__ready_answer">
            <p class="articles_create__ready_answer-letter">{{ variant.title }}</p>
            <input :id="'text-'+variant.itemId" type="text" v-model="variant.text">
            <div class="articles_create-checkbox">
              <input type="checkbox" v-model="variant.right"
                     @click="setCorrect(variant.itemId, variant.right)">
              <i></i>
              <p>Правильный ответ</p>
            </div>
          </div>
        </div>
        <p class="errors">{{errors.correct}}</p>
      </div>
      <div class="articles_create__item">
        <div class="articles_create__item-title has_radio">
          <input type="radio" :name="'answer_'+variant.itemId"
                 :value="2"
                 v-model="localType"
                />
          <i></i>
          <p>Поле ввода ответа</p>
        </div>
        <div class="articles_create__item-content">
          <textarea v-model.lazy="variant.variant"></textarea>
        </div>
      </div>
      <div class="articles_create__item">
        <div class="articles_create__item-title has_radio">
          <input type="radio"
                 v-model="localType"
                 :value="3"
                 :name="'answer_'+variant.itemId" />
          <i></i>
          <p>Медиа</p>
        </div>
        <div class="articles_create__item-content">
          <div class="articles_create__media">
            <SimpleTestMedia :media="variant.media"></SimpleTestMedia>
            <div class="articles_create__media-add">
              <input type="file" name="file" :id="'file-'+variant.itemId"
                     :disabled=" localType != 3"
                     @change="addMedia(index, variant.itemId, $event)">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--<div v-for="(variant, index) in variants" v-bind:key="variant.itemId">
        <div class="row mb-4">
            <div class="article-edit__text col-3">
                <div class="article-edit__btn-pos custom-checkbox">
                    <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                           v-bind:id="variant.itemId" v-model.lazy="answer.type" value="variants">
                    <label v-bind:for="variant.itemId" class="custom-checkbox__label"></label>
                    Готова вiдповiдь
                </div>
            </div>
            <div class="col-1">
                {{ variant.title }}
            </div>
            <div class="col-5">
                <div class="row">
                    <div class="col-12 mb-2">
                        <input class="form-control" type="text" name="title" placeholder=""
                               v-model.lazy="variant.variant">
                    </div>
                </div>
            </div>
            <div class="article-edit__text col-3">
                <div class="article-edit__btn-pos custom-checkbox">
                    <input class="custom-checkbox__input" type="checkbox" :name="'right-answer' + variant.itemId"
                           v-bind:id="variant.itemId + variant.title" v-model.lazy="'right-answer' + variant.itemId"
                           :value="variant.variant">
                    <label v-bind:for="variant.itemId + variant.title" class="custom-checkbox__label"></label>
                </div>
                Правильна вiдповiдь
            </div>
        </div>
        <div class="row mb-3">
            <div class="article-edit__text col-3">
                <div class="article-edit__btn-pos custom-checkbox">
                    <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                           v-bind:id="variant.itemId + '-field'" v-model.lazy="answer.type" value="text">
                    <label v-bind:for="variant.itemId + '-field'" class="custom-checkbox__label"></label>
                </div>
                Поле для вiдповiдi
            </div>
            <div class="col-9">
                <textarea class="form-control" rows="4" v-model.lazy="variant.variant"></textarea>
            </div>
        </div>
        <div class="row mb-4">
            <div class="article-edit__text col-3">
                <div class="article-edit__btn-pos custom-checkbox">
                    <input class="custom-checkbox__input" type="radio" v-bind:name="variant.itemId"
                           v-bind:id="variant.itemId + '-media'" v-model.lazy="answer.type" value="card">
                    <label v-bind:for="variant.itemId + '-media'" class="custom-checkbox__label"></label>
                </div>
                Медiа
            </div>
            <div class="col-4">
                <label class="btn btn-outline-second btn-centered-content upload-cover is-small">
                        <span class="input-file-label">
                            <span class="icon-is-left icon-is-load-grey"></span><span v-if="variant.file">{{
                                variant.file.file_name
                            }}</span><span v-else>Завантажити</span>
                        </span>

                    <input type="file" name="testCover" img_type="button" class="input-file-hidden"
                           v-on:change="handleUpload(index, $event)">
                </label>
            </div>
        </div>
    </div>-->
  </div>
</template>

<script>
import {required} from 'vuelidate/lib/validators'
import {PROJECT_IMAGE, TOKEN, ARTICLE_COVER} from "../../api/endpoints";
import SimpleTestMedia from "../fragmets/SimpleTestMedia"
import {getRandomId} from '../../utils'

export default {
  name: 'SimpleTestVariantsArray',
  components: {
    SimpleTestMedia
  },
  props: {
    variants: Array,
    type: Number,
    errors: Object
  },
  data() {
    return  {
      localType:Number
    }
  },
  mounted() {
    this.localType = this.type;
  },
  watch: {
    localType() {
      this.$store.commit('setQuestionType', this.localType);
    }
  },
  methods: {
    setCorrect(id, data) {
      if (!data) {
        this.$store.commit('addCorrect', id);
        this.errors.correct = '';
      } else {
        this.$store.commit('removeCorrect', id);
        if (! this.$store.state.test.answer.correct.length ){
          this.errors.correct = 'Має бути вказана принаймні одна правильна відповідь';
        }
      }
    },

    /**
     * Handle changing of file input (cover, video, variants)
     * @param event
     */
    handleUpload(index, event) {

      let imageForm = new FormData();
      let input = event.target
      let type = input.getAttribute('img_type')


      imageForm.append('file', input.files[0]);

      this.$post(PROJECT_IMAGE + type,
          imageForm,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
      ).then((file) => {
        this.variants[index].file = file.data
      })
    },
    hasActiveCheckbox(id, index) {
      return;

      // var sList = [];
      // $('#block-' + id + ' input[type=checkbox]').each(function () {
      //   if (this.id != 'right_answer_' + id) {
      //     var sThisVal = (this.checked ? 1 : 0);
      //     sList.push(sThisVal)
      //   }
      // });
      //
      // if (!sList.includes(1)) {
      //   this.$set(this.variants[index].answer, 'type', true)
      //   $('#modalAlertTest').modal('show')
      //   $('#type-' + id).prop('checked', true)
      // }
    },
    addMedia(index, id, event) {
      let imageForm = new FormData()
      imageForm.append('file', event.target.files[0])

      axios.post(
          ARTICLE_COVER + '/test',
          imageForm,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            },
            params: {
              access_token: TOKEN
            },
          }
      ).then((file) => {

        let obj = {
          itemId: getRandomId(),
          file: file.data.data.id,
          name: event.target.files[0].name,
          data: file.data,
          path: file.data.data.path
        };
        this.variants[index].media.push(obj)
        $('#file-' + id).val(null);
      })
    }
  },
  validations: {
    text: {}
  },
}
</script>

<style>
.custom-checkbox__input {
  margin-right: 10px;
}
</style>
