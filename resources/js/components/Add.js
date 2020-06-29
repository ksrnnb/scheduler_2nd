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

// class Memo extends React.Component {
//   render() {
//     return (
//       <div>
//         <p className="input-title">Memo (optional)</p>
//         <textarea name="memo" />
//       </div>
//     );
//   }
// }

// class Calender extends React.Component {
//   render () {
//     return (
//       <div>
//         <p className="input-title">Calender</p>
//       </div>
//     );
//   }
// }

// class CandidatesList extends React.Component {
//   render () {
//     return (
//       <div>
//         <p className="input-title">Candidates List</p>
//         <textarea name="candidates"/>
//       </div>
//     );
//   }
// }

// class MakeScheduleButton extends React.Component {
//   render () {
//     return (
//       <div>
//         <input type="submit" value="Create" />
//       </div>
//     );
//   }
// }

class Schedule extends React.Component {
  render() {
    return (
      <div>
        <ScheduleName />
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