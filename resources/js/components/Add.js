import React from 'react';
import ReactDOM from 'react-dom';
import { divide } from 'lodash';

class Candidates extends React.Component {

  getTrElements(candidates) {

    return Array.prototype.map.call(candidates, function(candidate) {
      return <tr key={candidate.name}><td>{candidate.name}</td><td>○</td><td>△</td><td>×</td></tr>;
    });

  }

  render() {
    let candidates = document.getElementsByClassName('candidates');

    return (
      <div>
        <table className="table">
          <thead>
            <tr>
              <th>Date</th><th></th><th></th><th></th>
            </tr>
          </thead>
          <tbody>
            {this.getTrElements(candidates)}
          </tbody>
        </table>
      </div>
    );
  }
}


if (document.getElementById('add')) {
  ReactDOM.render(
  <Candidates />,
    document.getElementById('add')
  );
}