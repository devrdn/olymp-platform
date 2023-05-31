document.querySelectorAll('.tabs-trigger__item').forEach((item) =>
  item.addEventListener('click', function (e) {
    const id = e.target.getAttribute('href').replace('#', '');

    e.preventDefault();

    document
      .querySelectorAll('.tabs-trigger__item')
      .forEach((child) => child.classList.remove('active-trigger'));

    document
      .querySelectorAll('.tabs-content__item')
      .forEach((child) => child.classList.remove('active-tab'));

    item.classList.add('active-trigger');
    document.getElementById(id).classList.add('active-tab');
  }),
);
