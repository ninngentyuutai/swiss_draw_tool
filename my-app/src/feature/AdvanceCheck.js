import React, { useEffect } from 'react';
import { BrowserRouter, Route, Routes, useNavigate, useLocation } from 'react-router-dom';




function AdvanceCheck() {
    return (
      <BrowserRouter>
        <Routes>
          {/* ログインしてたらログイントップにリダイレクト */}
          <Route path="/" Component={IsLoginToUserTop}></Route>
          <Route path="/login" Component={IsLoginToUserTop}></Route>
          
          {/* ログインしてなかったら非ログイントップにリダイレクト */}
          <Route path="/user-top" Component={IsNotLoginToTop}></Route>
          <Route path="/my-page/edit" Component={IsNotLoginToTop}></Route>
          <Route path="/add-tournament" Component={IsNotLoginToTop}></Route>
          <Route path="/tournament/participate" Component={IsNotLoginToTop}></Route>


          {/* ログイン中かつ参加登録中の場合参加者情報にリダイレクト */}
          {/* <Route path="/tournament" Component={Tournament}></Route> */}
          {/* 主催してなかったらログイントップにリダイレクト */}
          {/* <Route path="/tournament/edit" Component={TournamentEdit}></Route> */}
          {/* 主催、参加してなかったらログイントップにリダイレクト */}
          {/* <Route path="/tournament/participants" Component={TournamentParticipants}></Route> */}
          {/* 参加してなかったらログイントップにリダイレクト */}
          {/* <Route path="/tournament/participants/edit" Component={TournamentParticipantsEdit}></Route> */}

            
            {/* 試合ＩＤに合致するuser以外はログイントップにリダイレクト */}

 	



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
          navigate("/user-top");
        }
      }, [navigate]);
    return <div></div>;
  }

  function IsNotLoginToTop() {
    const location = useLocation();
    const pathname = location.pathname;
    const navigate = useNavigate();
    const islogin = JSON.parse(document.getElementById('islogin').textContent);
    useEffect(() => {
        if (islogin) {
          navigate("/");
        } else {
          navigate(pathname);
        }
      }, [navigate]);
    return <div></div>;
  }

  // function viewTournament() {
  //   const location = useLocation();
  //   const pathname = location.pathname;
  //   const navigate = useNavigate();
  //   const islogin = JSON.parse(document.getElementById('islogin').textContent);
  //   useEffect(() => {
  //       if (islogin && api結果) {
  //         navigate("/");
  //       } else {
  //         navigate(pathname);
  //       }
  //     }, [navigate]);
  //   return <div></div>;
  // }


export default AdvanceCheck;