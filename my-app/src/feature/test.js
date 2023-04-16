import ApiControl from './../common/ApiControl';
import { useCookies } from 'react-cookie';

  function Test() {
    const [cookies, setCookie] = useCookies(['cookie-name']);
    setCookie('api_key', 'aiueo');

    const params = {'action': 'user/islogin', 'params':{'api_key':'aiue'}};
    const test = ApiControl(params);
      return (<div>{test.result}</div>);
    }


export default Test;