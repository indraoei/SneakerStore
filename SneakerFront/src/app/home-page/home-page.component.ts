import { Component, OnInit } from '@angular/core';
import { Observable } from 'rxjs/Observable';
import { Http, Headers, RequestOptions } from '@angular/http';


@Component({
  selector: 'app-home-page',
  templateUrl: './home-page.component.html',
  styleUrls: ['./home-page.component.css']
})
export class HomePageComponent implements OnInit {

  productList : object[];

  constructor(private http: Http) { }

  ngOnInit() {
    var token = sessionStorage.getItem("token");
    console.log("token " + token);
    var hdr = new Headers({ "Authorization" : "Bearer" + token});
    var options = new RequestOptions({ headers : hdr });

    this.http.get('http://localhost:8000/api/product/getproduct', options)
    .subscribe(
      result => {
        this.productList = result.json();
        console.log(this.productList);
      },
      error => {
        console.log(error)
      }
    )
  }
  

}
