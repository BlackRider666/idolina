import axios from "axios";

const state = {
    directions: [],
};

const getters = {
    getDirections: (state) => state.directions,
};

const actions = {
    downloadDirections({commit}) {
        return new Promise(((resolve, reject) => {
            axios.get('directions')
                .then(res => {
                    commit("UPDATE_DIRECTIONS", res.data.data);
                    resolve(res.data.data)
                })
                .catch(errors => {
                    reject(errors.response.data)
                })
        }))
    },
};

const mutations = {
    UPDATE_DIRECTIONS (state, directions) {
        state.directions = directions
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}