// import { useParams, useLocation } from 'react-router-dom';
import ApiControl from './../common/ApiControl';


function UserDitail() {
  const params = {'action': 'user/login'};
  const test = ApiControl(params);
    return (<div>{test.result}</div>);
  }

export default UserDitail;