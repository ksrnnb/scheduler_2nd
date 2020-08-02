import React from 'react';
import ReactDOM from 'react-dom';

class ScheduleName extends React.Component {
  render() {
    return (
      <div>
        <p className="input-title">Schedule Name</p>
        <input type="text" name="scheduleName" required />
      </div>
    );
  }
}

class Memo extends React.Component {
  render() {
    return (
      <div>
        <p className="input-title">Memo (optional)</p>
        <textarea name="memo" />
      </div>
    );
  }
}

class Calender extends React.Component {

  handleClick(e) {
  
    e.target.classList.toggle('selected');

    // if (e.target.classList.contains('selected')) {
    //   e.target.classList.remove('selected');
    // } else {
    //   e.target.classList.add('selected');
    // }
    
  }

  render () {

    const weeks = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    const today = new Date();
    let year = today.getFullYear();
    let month = today.getMonth();

    const monthEndDay = new Date(year, month + 1, 0).getDate();

    const lastMonthEndDay = new Date(year, month, 0).getDate();
    const lastMonthEndWeek = new Date(year, month, 0).getDay();

    const weeksTableData = [];

    weeks.forEach(week => {
      weeksTableData.push(<td key={week}>{week}</td>);
    });

    const dateTableRowData = [];

    let date = 1;


    for (let row = 0; row < 6; row++) {

      const tableData = [];

      for (let week = 0; week < 7; week++) {
        const key = 'key' + row + '_' + week;

        if (row === 0) {
          if (week <= lastMonthEndWeek) {
            tableData.push(<td key={key} className="disable">{lastMonthEndDay - lastMonthEndWeek + week}</td>);
            continue;
          } else {
            tableData.push(<td onClick={this.handleClick} key={key}>{date}</td>);
          }
        } else {
          if (date <= lastMonthEndDay) {
            tableData.push(<td onClick={this.handleClick} key={key}>{date}</td>)
          } else {
            tableData.push(<td key={key} className="disable">{date - monthEndDay}</td>)
          }
        }
        date++;
      }

      dateTableRowData.push(<tr key={row}>{tableData}</tr>);

    }



    return (
      <div>
        <p className="input-title">Calender</p>
        <h2>{year}/{month + 1}</h2>
        <table>
          <thead>
            <tr>
              {weeksTableData}
            </tr>
          </thead>
          <tbody>
            {dateTableRowData}
          </tbody>
        </table>
      </div>
    );
  }
}

class CandidatesList extends React.Component {
  render () {
    return (
      <div>
        <p className="input-title">Candidates List</p>
        <textarea name="candidates" required readOnly />
      </div>
    );
  }
}

class MakeScheduleButton extends React.Component {
  render () {
    return (
      <div>
        <input type="submit" value="Create" />
      </div>
    );
  }
}

class Schedule extends React.Component {
  render() {
    return (
      <div>
        <ScheduleName />
        <Memo />
        <Calender />
        <CandidatesList />
        <MakeScheduleButton />
      </div>
    );
  }
}

if (document.getElementById('index')) {
  ReactDOM.render(
    <Schedule />,
    document.getElementById('index')
  );
}