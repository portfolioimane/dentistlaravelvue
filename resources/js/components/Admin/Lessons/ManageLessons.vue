<template>
  <div class="lessons">
    <h1>Manage Lessons for Course: {{ courseTitle }}</h1>
    
    <button class="btn primary" @click="addLesson">Add New Lesson</button>
    
    <table class="lessons-table">
      <thead>
        <tr>
          <th>Lesson ID</th>
          <th>Title</th>
          <th>Content</th>
          <th>Video Embed Code</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="lesson in allLessons" :key="lesson.id">
          <td>{{ lesson.id }}</td>
          <td>{{ lesson.title }}</td>
          <td>{{ lesson.content }}</td>
          <td v-html="getModifiedEmbedCode(lesson.video_embed_code)"></td>
          <td>
            <button class="btn secondary" @click="editLesson(lesson)">Edit</button>
            <button class="btn danger" @click="openDeleteModal(lesson.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Are you sure you want to delete this lesson?</h3>
        <div class="modal-actions">
          <button class="btn danger" @click="deleteLesson">Yes, Delete</button>
          <button class="btn primary" @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'ManageLessons',
  data() {
    return {
      showDeleteModal: false,
      lessonToDelete: null,
      message: null,
    };
  },
  computed: {
    ...mapGetters('backendCourses', ['currentCourse']),
    ...mapGetters('backendLessons', ['allLessons']),
    courseTitle() {
      return this.currentCourse ? this.currentCourse.title : 'Loading...';
    }
  },
  methods: {
    async fetchLessons() {
      const courseId = this.$route.params.courseId;
      await this.$store.dispatch('backendCourses/fetchCourseById', courseId);
      await this.$store.dispatch('backendLessons/fetchLessonsByCourse', courseId);
    },
    addLesson() {
      this.$router.push({ name: 'AddLesson', params: { courseId: this.$route.params.courseId } });
    },
editLesson(lesson) {
  this.$router.push(`/admin/courses/${this.$route.params.courseId}/lessons/edit/${lesson.id}`);
},

    openDeleteModal(lessonId) {
      this.lessonToDelete = lessonId;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.lessonToDelete = null;
    },
    async deleteLesson() {
      try {
        await this.$store.dispatch('backendLessons/deleteLesson', this.lessonToDelete);
        this.closeDeleteModal();
        this.showMessage('Lesson deleted successfully', 'success');
        this.fetchLessons();
      } catch (error) {
        this.showMessage('Error deleting lesson', 'error');
        console.error('Error deleting lesson:', error);
      }
    },
    showMessage(text, type) {
      this.message = { text, type };
      setTimeout(() => {
        this.message = null;
      }, 5000);
    },
    getModifiedEmbedCode(embedCode) {
      // Adjust the iframe width and height here
      return embedCode.replace(/width="(\d+)"/, 'width="150"').replace(/height="(\d+)"/, 'height="150"');
    }
  },
  mounted() {
    this.fetchLessons();
  },
};
</script>

<style scoped>
.lessons {
  padding: 30px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
  color: #1E90FF;
  margin-bottom: 20px;
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
}

.lessons-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.lessons-table th,
.lessons-table td {
  border: 1px solid #ddd;
  padding: 15px;
  text-align: left;
}

.lessons-table th {
  background-color: #1E90FF;
  color: white;
  font-weight: bold;
}

.lessons-table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

.lessons-table tbody tr:hover {
  background-color: #f9f9f9;
}

.btn {
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
  font-weight: bold;
}

.btn.secondary {
  background-color: #1E90FF;
  color: white;
}

.btn.danger {
  background-color: #e74c3c;
  color: white;
}

.btn.primary {
  background-color: #1E90FF;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  width: 100%;
  text-align: center;
}

.modal-actions {
  margin-top: 20px;
}

.modal-actions button {
  margin: 0 10px;
}

</style>
