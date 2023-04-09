//import { useEffect } from 'react';
import React, { useEffect } from 'react';

import { BrowserRouter, Route, Routes, useNavigate, useLocation } from 'react-router-dom';
import Test from './../test';


function AdvanceCheck() {
    return (
      <BrowserRouter>
        <Routes>
          {/* ログインしてたらログイントップにリダイレクト */}
          <Route path="/" Component={IsLoginToUserTop}></Route>
          {/* <Route path="/login" Component={IsLoginTologinTop}></Route> */}

          <Route path="/login" Component={Test}></Route>


          {/* <Route path="/test" Component={Test}></Route> */}


      </Routes>

      </BrowserRouter>
    );
  }

  function IsLoginToUserTop() {
    const location = useLocation();
    const pathname = location.pathname;
    const navigate = useNavigate();
    const islogin = JSON.parse(document.getElementById('islogin').textContent);
        useEffect(() => {
            if (islogin) {
              navigate(pathname);
            } else {
              navigate("/login");
            }
          }, [navigate]);


        return <div>
            <p>{pathname}</p>
            条件合致した場合はこのテキスト表示させる</div>;

    }


export default AdvanceCheck;