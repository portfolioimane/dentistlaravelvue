<template>
  <div class="edit-lesson">
    <h1>Edit Lesson</h1>
    <form @submit.prevent="submitForm" class="lesson-form">
      <div class="form-group">
        <label for="title">Lesson Title:</label>
        <input
          type="text"
          id="title"
          v-model="lesson.title"
          required
          placeholder="e.g., Introduction to Vue.js"
        />
      </div>

      <div class="form-group">
        <label for="content">Content:</label>
        <textarea
          id="content"
          v-model="lesson.content"
          required
          placeholder="Write the lesson content here"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="video_embed_code">Video Embed Code:</label>
        <textarea
          id="video_embed_code"
          v-model="lesson.video_embed_code"
          placeholder="e.g., YouTube embed code"
        ></textarea>
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Update Lesson</button>
        <router-link :to="`/admin/courses/${resolvedCourseId}/lessons`" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return {
      lesson: {
        title: '',
        content: '',
        video_embed_code: '',
      },
    };
  },
  computed: {
    ...mapGetters('backendLessons', ['currentLesson']),
    resolvedCourseId() {
      return this.$route.params.courseId;
    },
    resolvedLessonId() {
      return this.$route.params.lessonId;
    },
  },
  created() {
    this.fetchLesson();
  },
  watch: {
    resolvedLessonId(newId) {
      this.fetchLesson(newId);
    },
  },
  methods: {
    ...mapActions('backendLessons', ['fetchLessonById']),

    async fetchLesson() {
      await this.fetchLessonById(this.resolvedLessonId);
      this.lesson = { ...this.currentLesson };
    },

    async submitForm() {
      const formData = new FormData();
      formData.append('title', this.lesson.title);
      formData.append('content', this.lesson.content);
      formData.append('video_embed_code', this.lesson.video_embed_code);

      try {
        await this.$store.dispatch('backendLessons/updateLesson', {
          lessonId: this.resolvedLessonId,
          courseId: this.resolvedCourseId,
          lessonData: formData,
        });
        this.$router.push(`/admin/courses/${this.resolvedCourseId}/lessons`);
      } catch (error) {
        console.error('Error updating lesson:', error);
      }
    },
  },
};
</script>



<style scoped>
.edit-lesson {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.edit-lesson h1 {
  color: #1e90ff;
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
.lesson-form input[type="number"],
.lesson-form textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.9rem;
}

.lesson-form input:focus,
.lesson-form textarea:focus {
  border-color: #d4af37;
  outline: none;
}

.lesson-form textarea {
  resize: vertical;
  min-height: 80px;
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
  background-color: #1e90ff;
  color: #fff;
}

.btn.secondary {
  background-color: #555;
  color: #fff;
}

.btn:hover {
  opacity: 0.9;
}

@media (max-height: 768px) {
  .edit-lesson {
    margin: 1rem auto;
  }

  .lesson-form input,
  .lesson-form textarea {
    padding: 0.4rem;
    font-size: 0.85rem;
  }

  .btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
  }
}
</style>
