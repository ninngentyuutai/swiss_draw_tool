import axios from "axios";
import React from "react";


function ApiControl(props) {
  const baseURL = "http://localhost/swiss_draw_tool/app_dir/public/api/" + props.action;
  const [post, setPost] = React.useState(null);

  React.useEffect(() => {
    axios.post(baseURL).then((response) => {
        setPost(response.data);

    });
  }, [post]);

  if (!post) {
    return <div></div>;
  } else {
    return (post);
  }
  

}
export default ApiControl;