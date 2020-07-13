import React from 'react';
import ReactDOM from 'react-dom';
import { divide } from 'lodash';


class GetTableRows extends React.Component {
  constructor(props) {
    super(props);

    // これでいい？　あとcandidatesも欲しい。state？
    this.handleClick = this.props.onClick.bind(this);
  }

  render() {

    
    // console.log(this.props.onClick);
    // this.handleClick = this.props.onClick.bind(this);
    const rows = Array.prototype.map.call(this.props.candidates, function(candidate) {
      // need to use key
      //ここでthis.props.onClickやるとpropsがundefined...意味わからん
      // return <tr key={candidate.name} scope="row"><td>{candidate.name}</td><td onClick={this.props.onClick}>○</td><td>△</td><td>×</td></tr>;
      return <tr key={candidate.name} scope="row"><td>{candidate.name}</td><td>○</td><td>△</td><td>×</td></tr>;
    });

    return rows;
  }
}

class Candidates extends React.Component {

  constructor(props) {
    super(props);

    // bind is necessary...
    this.handleClick = this.handleClick.bind(this);
  }

  handleClick() {
    alert('Hey');
  }


// もうちょい良い書き方あるはず、、、
  // getTrElements(candidates) {

  //   // I use map because forEach returns undefined
  //   return Array.prototype.map.call(candidates, function(candidate) {
  //     // need to use key
  //     return <tr key={candidate.name} scope="row"><td>{candidate.name}</td><td onClick={handleClick}>○</td><td>△</td><td>×</td></tr>;
  //   });

  // }

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
            {/* {this.getTrElements(candidates)} */}
            <GetTableRows onClick={this.handleClick} candidates={candidates}/>
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