import CourseDiscountComponent from './components/CourseDiscountComponent'
if (typeof vueApp !== 'undefined') {
    vueApp.booting((vue) => {
        vue.component('course-discount-component', CourseDiscountComponent)
    })
}
