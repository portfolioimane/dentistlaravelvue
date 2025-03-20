<template>
  <div class="add-lesson">
    <h1>Add New Lesson</h1>
    <form @submit.prevent="submitForm" class="lesson-form">
      <div class="form-group">
        <label for="title">Lesson Title:</label>
        <input
          type="text"
          id="title"
          v-model="newLesson.title"
          required
          placeholder="e.g., Introduction to Vue.js"
        />
      </div>

      <div class="form-group">
        <label for="content">Content:</label>
        <textarea
          id="content"
          v-model="newLesson.content"
          required
          placeholder="Write the lesson content here"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="video_embed_code">Video Embed Code:</label>
        <textarea
          id="video_embed_code"
          v-model="newLesson.video_embed_code"
          placeholder="e.g., YouTube embed code"
        ></textarea>
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Add Lesson</button>
        <router-link :to="`/admin/courses/${courseId}/lessons`" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    courseId: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      newLesson: {
        title: '',
        content: '',
        video_embed_code: '', // Store embed code as text
      },
    };
  },
  computed: {
    resolvedCourseId() {
      return this.courseId || this.$route.params.courseId;
    },
  },
  methods: {
    async submitForm() {
      try {
        await this.$store.dispatch('backendLessons/addLesson', {
          courseId: this.resolvedCourseId,
          lessonData: this.newLesson,
        });
        this.$router.push(`/admin/courses/${this.resolvedCourseId}/lessons`);
      } catch (error) {
        console.error('Error adding lesson:', error);
      }
    },
  },
};
</script>

<style scoped>
.add-lesson {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.add-lesson h1 {
  color: #1E90FF;
  font-size: 1.6rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
}

.lesson-form .form-group {
  margin-bottom: 0.5rem;
}

.lesson-form label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.25rem;
  color: #555;
}

.lesson-form input[type="text"],
.lesson-form input[type="url"],
.lesson-form textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.9rem;
}

.button-group {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  font-size: 0.9rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  text-transform: uppercase;
}

.btn.primary {
  background-color: #1E90FF;
  color: #fff;
}

.btn.secondary {
  background-color: #555;
  color: #fff;
}

.btn:hover {
  opacity: 0.9;
}
</style>
