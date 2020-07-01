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
    let candidates = document.getElementById('candidates').value;
    return (
      <div>
        <p>Candidates</p>
        <input type="text" value={candidates} readOnly />
      </div>
    );
  }
}

class Schedule extends React.Component {
  render() {
    return (
      <div>
        <ScheduleName />
        <Url />
        <Candidates />
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