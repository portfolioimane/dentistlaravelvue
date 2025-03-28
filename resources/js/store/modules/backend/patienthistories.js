import axios from '../../../utils/axios.js';

const state = {
  allPatientHistories: [],
  currentPatientHistory: {},
};

const getters = {
  allPatientHistories: state => state.allPatientHistories,
  currentPatientHistory: state => state.currentPatientHistory,
};

const actions = {
  async fetchHistoriesByPatient({ commit }, patientId) {
    try {
      const response = await axios.get(`/admin/patients/${patientId}/histories`);
      commit('setPatientHistories', response.data);
    } catch (error) {
      console.error(error);
    }
  },
  async fetchPatientHistoryById({ commit }, historyId) {
    try {
      const response = await axios.get(`/admin/patient-histories/${historyId}`);
      commit('setCurrentPatientHistory', response.data);
      return response; // Return the response so the component can access the data
    } catch (error) {
      console.error(error);
    }
  },
  async deletePatientHistory({ dispatch }, historyId) {
    try {
      await axios.delete(`/admin/patient-histories/${historyId}`);
      dispatch('fetchHistoriesByPatient', historyId);
    } catch (error) {
      console.error(error);
    }
  },
  async addPatientHistory({ dispatch }, { patientId, historyData }) {
    try {
      await axios.post(`/admin/patients/${patientId}/histories`, historyData);
      dispatch('fetchHistoriesByPatient', patientId);
    } catch (error) {
      console.error(error);
    }
  },
  async updatePatientHistory({ dispatch }, { historyId, patientId, historyData }) {
    try {
      await axios.post(`/admin/patient-histories/${historyId}`, historyData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        params: {
          _method: 'PUT', // Correct usage of PUT for update
        },
      });

      dispatch('fetchHistoriesByPatient', patientId); // Fetch histories again for the updated patient
    } catch (error) {
      console.error('Error updating patient history:', error);
    }
  },
};

const mutations = {
  setPatientHistories: (state, histories) => {
    state.allPatientHistories = histories;
  },
  setCurrentPatientHistory: (state, history) => {
    state.currentPatientHistory = history;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
