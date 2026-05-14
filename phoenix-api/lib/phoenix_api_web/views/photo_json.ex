defmodule PhoenixApiWeb.PhotoJSON do
  use PhoenixApiWeb, :view

  def render("photos.json", %{photos: photos}) do
    %{photos: render_many(photos, __MODULE__, "photo.json", as: :photo)}
  end

  def render("photo.json", %{photo: photo}) do
    %{
      id: photo.id,
      photo_url: photo.photo_url,
      camera: photo.camera,
      lens: photo.lens,
      settings: photo.settings,
      description: photo.description,
      location: photo.location,
      focal_length: photo.focal_length,
      aperture: photo.aperture,
      shutter_speed: photo.shutter_speed,
      iso: photo.iso,
      taken_at: photo.taken_at
    }
  end
end
