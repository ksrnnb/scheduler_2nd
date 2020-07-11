import React from 'react';
import ReactDOM from 'react-dom';
import { divide } from 'lodash';

class Candidates extends React.Component {

  getTrElements(candidates) {

    // I use map because forEach returns undefined
    return Array.prototype.map.call(candidates, function(candidate) {
      // need to use key
      return <tr key={candidate.name} scope="row"><td>{candidate.name}</td><td onClick={() => alert('Hey')}>○</td><td>△</td><td>×</td></tr>;
    });

  }

  render() {
    // const or let ??
    let candidates = document.getElementsByClassName('candidates');

    return (
      <div>
        {/* Need to adjust table width later */}
        <table className="table-bordered text-center" style={{width: "80%"}}>
          <thead>
            <tr>
              <th>Date</th><th scope="col"></th><th scope="col"></th><th scope="col"></th>
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