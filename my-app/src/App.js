import './App.css';
import { BrowserRouter, Route, Routes} from 'react-router-dom';
import UserDitail from './feature/userditail';
import Test from './test';

function App() {
    return (
      <BrowserRouter>
        <Routes>
          {/* match */}
          <Route path="/match/" Component={UserDitail}></Route>

          <Route path="/test" Component={Test}></Route>


      </Routes>

      </BrowserRouter>
    );
  }


export default App;
