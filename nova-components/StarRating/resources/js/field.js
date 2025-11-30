import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'
import PreviewField from './components/PreviewField'

Nova.booting((app, store) => {
  app.component('index-star-rating', IndexField)
  app.component('detail-star-rating', DetailField)
  app.component('form-star-rating', FormField)
  // app.component('preview-star-rating', PreviewField)
})
