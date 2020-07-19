import React from 'react';
import ReactDOM from 'react-dom';
import { divide } from 'lodash';

class GetTableData extends React.Component {
  constructor(props) {
    super(props);

    this.handleClick = this.props.onClick.bind(this);
  }

  render() {

    const symbols = ['○', '△', '×'];
    const handleClick = this.handleClick;
    const rowIndex = this.props.rowIndex;
    const availability = this.props.availability;
    
    const tableData = symbols.map((symbol, symbolIndex) => {

      if (availability === symbolIndex) {
        
        return <td key={symbolIndex} onClick={() => handleClick(rowIndex, symbolIndex)} className="selected">{symbol}</td>;
      } else {
        return <td key={symbolIndex} onClick={() => handleClick(rowIndex, symbolIndex)}>{symbol}</td>;
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
  }
  render() {

    const handleClick = this.handleClick;
    const availabilities = this.props.availabilities;

    const rows = Array.prototype.map.call(this.candidates, function(candidate, index) {
      // need to use key
      return <tr key={candidate.name} scope="row"><td>{candidate.name}</td><GetTableData onClick={handleClick} availability={availabilities[index]} rowIndex={index} /></tr>;
    });

    return rows;
  }
}

class Candidates extends React.Component {

  constructor(props) {
    super(props);

    this.candidates = document.getElementsByClassName('candidates');

    this.state = {
      availabilities: Array(this.candidates.length).fill(0),
    };

    // bind is necessary...
    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(rowIndex, symbolIndex) {
    const availabilities = this.state.availabilities.slice();
    availabilities[rowIndex] = symbolIndex;
    this.setState({
      availabilities: availabilities,
    });
  }


  render() {
    // const or let ??
    // const candidates = document.getElementsByClassName('candidates');
    const candidates = this.candidates;

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
            <GetTableRows onClick={this.handleClick} candidates={candidates} availabilities={this.state.availabilities}/>
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