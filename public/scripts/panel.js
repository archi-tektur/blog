const button = document.getElementById('menuSizeSwitcher');
const navigation = document.getElementById('navigation');
const classToToggle = 'nav--small';

button.addEventListener('click', () => {
  navigation.classList.toggle(classToToggle);
});
