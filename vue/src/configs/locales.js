import en from '../translations/en'
import ar from '../translations/ar'

const supported = ['en', 'ar']
let locale = 'ar'

try {
  // get browser default language
  const { 0: browserLang } = navigator.language.split('-')

  if (supported.includes(browserLang)) locale = browserLang
} catch (e) {
  console.log(e)
}

export default {
  // current locale
  locale: 'ar',

  // when translation is not available fallback to that locale
  fallbackLocale: 'en',

  // availabled locales for user selection
  availableLocales: [{
    code: 'en',
    flag: 'us',
    label: 'English',
    messages: en
  },{
    code: 'ar',
    flag: 'sa',
    label: 'العربية',
    messages: ar
  }]
}
