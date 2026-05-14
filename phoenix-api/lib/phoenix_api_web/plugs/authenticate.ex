defmodule PhoenixApiWeb.Plugs.Authenticate do
  import Plug.Conn
  import Phoenix.Controller
  alias PhoenixApi.Accounts
  alias PhoenixApi.Accounts.User

  def init(opts), do: opts

  def call(conn, _opts) do
    case get_req_header(conn, "access-token") do
      [token] ->
        case Accounts.get_by_api_token(token) do
          %User{} = user ->
            assign(conn, :current_user, user)

          nil ->
            conn
            |> put_status(:unauthorized)
            |> put_view(json: PhoenixApiWeb.ErrorJSON)
            |> render(:"401")
            |> halt()
        end

      [] ->
        conn
        |> put_status(:unauthorized)
        |> put_view(json: PhoenixApiWeb.ErrorJSON)
        |> render(:"401")
        |> halt()
    end
  end
end
