import { Component, OnInit } from '@angular/core';
import { Auteur } from '../models/auteur';
import { ApiService } from '../services/api.service';
import { Router } from '@angular/router';


@Component({
  selector: 'app-auteurs-list',
  templateUrl: './auteurs-list.component.html',
  styleUrl: './auteurs-list.component.css'
})
export class AuteursListComponent {
  auteurs: Auteur[] = [];

  constructor(
    private apiService: ApiService,
    private router: Router
    ) {}

  ngOnInit(): void {
    this.apiService.getAuteurs().subscribe((data: Auteur[]) => {
      this.auteurs = data;
    });
  }
  onLivreSelected(event: Event) {
    const titre = (event.target as HTMLInputElement).value;
    if (titre) {
      this.router.navigate(['/livre', titre]);
    }
  }
}
