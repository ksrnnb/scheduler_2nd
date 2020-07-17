import React from 'react';
import ReactDOM from 'react-dom';
import { divide } from 'lodash';

class GetTableData extends React.Component {
  constructor(props) {
    super(props);

    // handleClickをどう扱うか、、、親コンポーネントからもってくるべき？
    this.handleClick = this.props.onClick.bind(this);
    this.state = {
      availability: this.props.availability,
    }
  }


  // handleClick(symbolIndex) {
  //   this.setState({
  //     availability: symbolIndex,
  //   });
  // }

  render() {

    const symbols = ['○', '△', '×'];
    const handleClick = this.handleClick;
    const availability = this.availability;
    
    const tableData = symbols.map((symbol, index) => {

      if (availability === index) {
        //クリックしたらselectedが変わるようにするにはどうしたらいい？？
        return <td key={index} onClick={handleClick} className="selected">{symbol}</td>;
      } else {
        return <td key={index} onClick={handleClick}>{symbol}</td>;
      }
    });

    return tableData;
  }

  
}

class GetTableRows extends React.Component {
  constructor(props) {
    super(props);
    this.candidates = this.props.candidates;
    this.handleClick = this.props.onClick.bind(this);

    this.availabilities = Array(this.candidates.length).fill(0);

  }
  render() {

    const handleClick = this.handleClick;
    const availabilities = this.availabilities;

    const rows = Array.prototype.map.call(this.candidates, function(candidate, index) {
      // need to use key
      return <tr key={candidate.name} scope="row"><td>{candidate.name}</td><GetTableData onClick={handleClick} availability={availabilities[index]}/></tr>;
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
    alert('hey');
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