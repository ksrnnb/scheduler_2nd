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

  constructor(props) {
    super(props);
    this.handleClick = this.props.onClick.bind(this);
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

    // block scope (date)
    {
      let date = 1;

      const yearMonth = year + '/' + (month + 1) + '/';
      
      for (let row = 0; row < 6; row++) {

        const tableData = [];

        for (let week = 0; week < 7; week++) {
          const key = 'key' + row + '_' + week;

          if (row === 0) {

            if (week <= lastMonthEndWeek) {
              
              tableData.push(<td key={key} className="disable">{lastMonthEndDay - lastMonthEndWeek + week}</td>);
              continue;
            } else {
              tableData.push(<td onClick={this.handleClick} data-date={yearMonth + date} key={key}>{date}</td>);
            }
          } else {
            if (date <= lastMonthEndDay) {
              tableData.push(<td onClick={this.handleClick} data-date={yearMonth + date} key={key}>{date}</td>)
            } else {
              tableData.push(<td key={key} className="disable">{date - monthEndDay}</td>)
            }
          }
          date++;
        }

        dateTableRowData.push(<tr key={row}>{tableData}</tr>);

      }
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
    const weeks = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    let candidates = this.props.list.map(candidate => {
      const d = new Date(candidate);
      const month = d.getMonth();
      const date = d.getDate();
      const day = d.getDay();
      return month + '/' + date + ' (' + weeks[day] + ')'; 
    }
      );
    const list = candidates.join('\n');
    console.log(list);
    return (
      <div>
        <p className="input-title">Candidates List</p>
        <textarea name="candidates" required readOnly value={list}/>
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

  constructor(props) {
    super(props);
    this.state = {
      list: []
    };
  }

  handleClick(e) {

    let list = this.state.list.slice();

    const td = e.target;
    const date = td.dataset.date;

    if (td.classList.contains('selected')) {
      td.classList.remove('selected');
      list = list.filter(item => {
        return item != date;
      });
    } else {
      td.classList.add('selected');
      list.push(date);
      list.sort((a, b) => new Date(a) - new Date(b));

      if (list.length > 2) {
        console.log(new Date(list[0]) - new Date(list[1]));
        console.log(new Date(list[0]) > new Date(list[1]));
      }
    }

    this.setState({
      list: list
    });
    
  }

  render() {

    const handleClick = this.handleClick.bind(this);

    return (
      <div>
        <ScheduleName />
        <Memo />
        <Calender onClick={handleClick}/>
        <CandidatesList list={this.state.list}/>
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