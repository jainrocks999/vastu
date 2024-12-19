import CourseDiscountComponent from './components/DiscountComponent'
if (typeof vueApp !== 'undefined') {
    vueApp.booting((vue) => {
        vue.component('course-discount-component', CourseDiscountComponent)
    })
}
