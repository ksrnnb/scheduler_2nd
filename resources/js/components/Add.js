import React from 'react';
import ReactDOM from 'react-dom';

class ScheduleName extends React.Component {
  render() {
    let scheduleName = document.getElementById('scheduleName').value;
    return (
      <div>
        <p>{scheduleName}</p>
      </div>
    );
  }
}

class Url extends React.Component {
  render() {
    let url = document.getElementById('url').value;
    return (
      <div>
        <p>url</p>
        <input type="text" value={url} readOnly />
      </div>
    );
  }
}

class Candidates extends React.Component {

  render() {
    let candidates = document.getElementsByClassName('candidates');
    console.log(candidates);
    return (
      <div>
        <p>Candidates</p>
        <textarea />
      </div>
    );
  }
}

class Schedule extends React.Component {
  render() {
    return (
      <div>
        
      </div>
    );
  }
}

if (document.getElementById('add')) {
  ReactDOM.render(
  <Schedule />,
    document.getElementById('add')
  );
}