import ApiControl from '../common/ApiControl';
import { useCookies } from "react-cookie";


function IsApiLogin() {
  const action = 'user/islogin';
  const params = {'api_key': useCookies.api_key};
  const api = ApiControl(action, params);
    return api.result;
  }

export default IsApiLogin;