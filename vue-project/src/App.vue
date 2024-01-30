<script setup>
import axios from 'axios';
</script>

<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="card">
          <div class="card-header">
            <h4>URL Shortener</h4>
          </div>
          <div class="card-body">
            <div class="input-group">
              <input type="text" v-model="url" class="form-control" placeholder="Enter URL">
              <button class="btn btn-primary" @click="shortenUrl">Shorten</button>
            </div>
            <div class="mt-3" v-if="shortenedUrl">
              <a :href="shortenedUrl">{{ shortenedUrl }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      url: '',
      shortenedUrl: '',
    };
  },
  methods: {
    shortenUrl() {
      const pattern = /^((http|https):\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i;
      if (pattern.test(this.url)) {
        axios.post('http://localhost:8000/api/shorten', { url: this.url })
        .then((response) => {
          if (response.data.unsafe) {
            this.shortenedUrl = ''
            this.url = ''
            alert('URL is not safe!')
          } else {
            this.shortenedUrl = response.data.url;
          }
        });
      } else {
        alert('URL is not valid!')
      }
    },
  },
};
</script>

<style>
@import url(https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css);
</style>