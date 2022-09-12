export default {
    namespaced: true,
    state: {
        title: 'Bảng điều khiển',
        activeMenu: 0,
        loader: false
    },
    mutations: {
        changeTitle(state, data): void {
            state.title = data
        },
        setActiveMenu(state, data): void {
            state.activeMenu = data
        },
        setLoader(state, data): void {
            state.loader = data
        }
    },
}