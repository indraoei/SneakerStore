import { Component, OnInit } from '@angular/core';
import { Http, Headers, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import { Router } from '@angular/router';

@Component({
  selector: 'app-sign-up',
  templateUrl: './sign-up.component.html',
  styleUrls: ['./sign-up.component.css']
})
export class SignUpComponent implements OnInit {

  username:string;
  email:string;
  address:string;
  phone_number:number;
  password:string;
  confirm_pass:string;

  constructor(private http: Http, private router: Router) { }

  ngOnInit() {
  }

  SignUp(){
    if(this.password == this.confirm_pass){
      var body = JSON.stringify({
        "name" : this.username,
        "email" : this.email,
        "address" : this.address,
        "phone_number" : this.phone_number,
        "password" : this.password
      });
      var hdr = new Headers({ "Content-Type" : "application/json" });
      var options = new RequestOptions({ headers : hdr });

      this.http.post('http://localhost:8000/api/user/signup', body, options)
      .subscribe(
        result => {
          this.router.navigate(['/sign-in'])
        },
        error => {
          console.log(error)
        }
      )
    }
    else{alert("confirm password incorrect")}
  }

}
