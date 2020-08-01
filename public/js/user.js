'use strict';

const users = document.getElementsByClassName("users");

const user_name = document.getElementById('user-name');
const user_id_element = document.getElementById('user-id');

Array.prototype.forEach.call(users, (user) => {

  const user_id = user.dataset.id;


  user.addEventListener('click', () => {
    user_name.value = user.innerHTML;
    user_id_element.value = user_id;

  });

});