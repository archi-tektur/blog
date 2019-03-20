const button = document.getElementById('menuSizeSwitcher');
const navigation = document.getElementById('navigation');
const classToToggle = 'nav--small';

button.addEventListener('dblclick', () => {
  navigation.classList.toggle(classToToggle);
});



