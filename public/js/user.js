'use strict';

const users = document.getElementsByClassName("users");

const user_name = document.getElementById('user-name');
const user_id_element = document.getElementById('user-id');

const submit_button = document.getElementById('submit-button');

// action of clicking user name
Array.prototype.forEach.call(users, (user) => {

  const user_id = user.dataset.id;


  user.addEventListener('click', () => {
    user_name.value = user.innerHTML;
    user_id_element.value = user_id;
    submit_button.value = "Update user";

  });

});


const input_title = document.getElementById('input-title');

input_title.addEventListener('click', () => {
  submit_button.value = "Add user";
  user_name.value = "";
  user_id_element.value = "";

});
