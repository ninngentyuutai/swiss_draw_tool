import { useParams, useLocation } from "react-router-dom";




function UserDitail() {
    const { name } = useParams();
    const { search } = useLocation();
    console.log(name);
    console.log(search);

    return <div>aiueoxx</div>;
  
  }

export default UserDitail;