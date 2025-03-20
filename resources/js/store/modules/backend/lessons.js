import axios from '../../../utils/axios.js';

const state = {
  allLessons: [],
  currentLesson: {},
};

const getters = {
  allLessons: state => state.allLessons,
  currentLesson: state => state.currentLesson,
};

const actions = {
  async fetchLessonsByCourse({ commit }, courseId) {
    try {
      const response = await axios.get(`/admin/courses/${courseId}/lessons`);
      commit('setLessons', response.data);
    } catch (error) {
      console.error(error);
    }
  },
  async fetchLessonById({ commit }, lessonId) {
    try {
      const response = await axios.get(`/admin/lessons/${lessonId}`);
      commit('setCurrentLesson', response.data);
      return response; // Return the response so the component can access the data
    } catch (error) {
      console.error(error);
    }
  },
  async deleteLesson({ dispatch }, lessonId) {
    try {
      await axios.delete(`/admin/lessons/${lessonId}`);
      dispatch('fetchLessonsByCourse');
    } catch (error) {
      console.error(error);
    }
  },
  async addLesson({ dispatch }, { courseId, lessonData }) {
    try {
      await axios.post(`/admin/courses/${courseId}/lessons`, lessonData);
      dispatch('fetchLessonsByCourse', courseId);
    } catch (error) {
      console.error(error);
    }
  },
    async updateLesson({ dispatch }, { lessonId, courseId, lessonData}) {
    try {
      await axios.post(`/admin/lessons/${lessonId}`, lessonData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        params: {
          _method: 'PUT', // Correct usage of PUT for update
        },
      });

      dispatch('fetchLessonsByCourse', courseId); // Fetch lessons again for the updated course
    } catch (error) {
      console.error('Error updating course:', error);
    }
  },

};

const mutations = {
  setLessons: (state, lessons) => {
    state.allLessons = lessons;
  },
  setCurrentLesson: (state, lesson) => {
    state.currentLesson = lesson;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
